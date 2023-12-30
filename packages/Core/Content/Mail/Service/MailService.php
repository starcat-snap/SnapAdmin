<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Mail\Service;

use Monolog\Level;
use Psr\Log\LoggerInterface;
use SnapAdmin\Core\Content\MailTemplate\Exception\SalesChannelNotFoundException;
use SnapAdmin\Core\Content\MailTemplate\Service\Event\MailBeforeSentEvent;
use SnapAdmin\Core\Content\MailTemplate\Service\Event\MailBeforeValidateEvent;
use SnapAdmin\Core\Content\MailTemplate\Service\Event\MailErrorEvent;
use SnapAdmin\Core\Content\MailTemplate\Service\Event\MailSentEvent;
use SnapAdmin\Core\Content\Media\MediaCollection;
use SnapAdmin\Core\Framework\Adapter\Twig\StringTemplateRenderer;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityRepository;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Criteria;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Validation\EntityExists;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Plugin\Exception\DecorationPatternException;
use SnapAdmin\Core\Framework\Validation\DataValidationDefinition;
use SnapAdmin\Core\Framework\Validation\DataValidator;
use SnapAdmin\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

#[Package('system-settings')]
class MailService extends AbstractMailService
{
    /**
     * @param EntityRepository<MediaCollection> $mediaRepository
     * @internal
     *
     */
    public function __construct(
        private readonly DataValidator            $dataValidator,
        private readonly StringTemplateRenderer   $templateRenderer,
        private readonly AbstractMailFactory      $mailFactory,
        private readonly AbstractMailSender       $mailSender,
        private readonly EntityRepository         $mediaRepository,
        private readonly SystemConfigService      $systemConfigService,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly LoggerInterface          $logger
    )
    {
    }

    public function getDecorated(): AbstractMailService
    {
        throw new DecorationPatternException(self::class);
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $templateData
     */
    public function send(array $data, Context $context, array $templateData = []): ?Email
    {
        $event = new MailBeforeValidateEvent($data, $context, $templateData);
        $this->eventDispatcher->dispatch($event);
        $data = $event->getData();
        $templateData = $event->getTemplateData();

        if ($event->isPropagationStopped()) {
            return null;
        }

        $definition = $this->getValidationDefinition($context);
        $this->dataValidator->validate($data, $definition);

        $recipients = $data['recipients'];

        $senderEmail = $data['senderMail'] ?? $this->getSender($data);

        if ($senderEmail === null) {
            $event = new MailErrorEvent(
                $context,
                Level::Error,
                null,
                'senderMail not configured, Please check system_config \'core.basicInformation.email\'',
                null,
                $templateData
            );

            $this->eventDispatcher->dispatch($event);
            $this->logger->error(
                'senderMail not configured, Please check system_config \'core.basicInformation.email\'',
                $templateData
            );
        }

        $contents = $this->buildContents($data);
        if ($this->isTestMode($data)) {
            $this->templateRenderer->enableTestMode();
            if (\is_array($templateData['order'] ?? []) && empty($templateData['order']['deepLinkCode'])) {
                $templateData['order']['deepLinkCode'] = 'home';
            }
        }
        $template = $data['subject'];

        try {
            $data['subject'] = $this->templateRenderer->render($template, $templateData, $context, false);
            $template = $data['senderName'];
            $data['senderName'] = $this->templateRenderer->render($template, $templateData, $context, false);
            foreach ($contents as $index => $template) {
                $contents[$index] = $this->templateRenderer->render($template, $templateData, $context, $index !== 'text/plain');
            }
        } catch (\Throwable $e) {
            $event = new MailErrorEvent(
                $context,
                Level::Warning,
                $e,
                'Could not render Mail-Template with error message: ' . $e->getMessage(),
                $template,
                $templateData
            );
            $this->eventDispatcher->dispatch($event);
            $this->logger->warning(
                'Could not render Mail-Template with error message: ' . $e->getMessage(),
                array_merge([
                    'template' => $template,
                    'exception' => (string)$e,
                ], $templateData)
            );

            return null;
        }
        if ($this->isTestMode($data)) {
            $this->templateRenderer->disableTestMode();
        }

        $mediaUrls = $this->getMediaUrls($data, $context);

        $binAttachments = $data['binAttachments'] ?? null;

        $mail = $this->mailFactory->create(
            $data['subject'],
            [$senderEmail => $data['senderName']],
            $recipients,
            $contents,
            $mediaUrls,
            $data,
            $binAttachments
        );

        if (trim($mail->getBody()->toString()) === '') {
            $event = new MailErrorEvent(
                $context,
                Level::Error,
                null,
                'mail body is null',
                null,
                $templateData
            );

            $this->eventDispatcher->dispatch($event);
            $this->logger->error(
                'mail body is null',
                $templateData
            );

            return null;
        }

        $event = new MailBeforeSentEvent($data, $mail, $context, $templateData['eventName'] ?? null);
        $this->eventDispatcher->dispatch($event);

        if ($event->isPropagationStopped()) {
            return null;
        }

        $this->mailSender->send($mail);

        $event = new MailSentEvent($data['subject'], $recipients, $contents, $context, $templateData['eventName'] ?? null);
        $this->eventDispatcher->dispatch($event);

        return $mail;
    }

    /**
     * @param array<string, mixed> $data
     */
    private function getSender(array $data): ?string
    {
        $senderEmail = $data['senderEmail'] ?? null;

        if ($senderEmail !== null && trim((string)$senderEmail) !== '') {
            return $senderEmail;
        }

        $senderEmail = $this->systemConfigService->getString('core.basicInformation.email');

        if (trim($senderEmail) !== '') {
            return $senderEmail;
        }

        $senderEmail = $this->systemConfigService->getString('core.mailerSettings.senderAddress');

        if (trim($senderEmail) !== '') {
            return $senderEmail;
        }

        return null;
    }

    /**
     * Attaches header and footer to given email bodies
     *
     * @param array<string, mixed> $data
     * e.g. ['contentHtml' => 'foobar', 'contentPlain' => '<h1>foobar</h1>']
     *
     * @return array{'text/plain': string, 'text/html': string}
     * e.g. ['text/plain' => '{{foobar}}', 'text/html' => '<h1>{{foobar}}</h1>']
     *
     * @internal
     */
    private function buildContents(array $data): array
    {
        return [
            'text/html' => $data['contentHtml'],
            'text/plain' => $data['contentPlain'],
        ];
    }

    private function getValidationDefinition(Context $context): DataValidationDefinition
    {
        $definition = new DataValidationDefinition('mail_service.send');

        $definition->add('recipients', new NotBlank());
        $definition->add('contentHtml', new NotBlank());
        $definition->add('contentPlain', new NotBlank());
        $definition->add('subject', new NotBlank());
        $definition->add('senderName', new NotBlank());

        return $definition;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return list<string>
     */
    private function getMediaUrls(array $data, Context $context): array
    {
        if (empty($data['mediaIds'])) {
            return [];
        }
        $criteria = new Criteria($data['mediaIds']);
        $criteria->setTitle('mail-service::resolve-media-ids');
        $media = new MediaCollection();
        $context->scope(Context::SYSTEM_SCOPE, function (Context $context) use ($criteria, &$media): void {
            $media = $this->mediaRepository->search($criteria, $context)->getEntities();
        });

        $urls = [];
        foreach ($media as $mediaItem) {
            $urls[] = $mediaItem->getPath();
        }

        return $urls;
    }

    /**
     * @param array<string, mixed> $data
     */
    private function isTestMode(array $data = []): bool
    {
        return !empty($data['testMode']);
    }
}
