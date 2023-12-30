<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Document\Twig;

use SnapAdmin\Core\Content\Document\DocumentGenerator\Counter;
use SnapAdmin\Core\Content\Document\Event\DocumentTemplateRendererParameterEvent;
use SnapAdmin\Core\Framework\Adapter\Translation\Translator;
use SnapAdmin\Core\Framework\Adapter\Twig\TemplateFinder;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

#[Package('content')]
class DocumentTemplateRenderer
{
    /**
     * @internal
     */
    public function __construct(
        private readonly TemplateFinder $templateFinder,
        private readonly Environment $twig,
        private readonly Translator $translator,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
    }

    /**
     * @param array<string, mixed> $parameters
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render(
        string $view,
        array $parameters = [],
        ?Context $context = null,
        ?string $salesChannelId = null,
        ?string $languageId = null,
        ?string $locale = null
    ): string {
        // If parameters for specific language setting provided, inject to translator
        if ($context !== null && $salesChannelId !== null && $languageId !== null && $locale !== null) {
            $this->translator->injectSettings(
                $languageId,
                $locale,
                $context
            );
            $parameters['context'] = $context;
        }

        $documentTemplateRendererParameterEvent = new DocumentTemplateRendererParameterEvent($parameters);
        $this->eventDispatcher->dispatch($documentTemplateRendererParameterEvent);
        $parameters['extensions'] = $documentTemplateRendererParameterEvent->getExtensions();

        $parameters['counter'] = new Counter();

        $view = $this->resolveView($view);

        $rendered = $this->twig->render($view, $parameters);

        // If injected translator reject it
        if ($context !== null && $languageId !== null && $locale !== null) {
            $this->translator->resetInjection();
        }

        return $rendered;
    }

    /**
     * @throws LoaderError
     */
    private function resolveView(string $view): string
    {
        $this->templateFinder->reset();

        return $this->templateFinder->find($view);
    }
}
