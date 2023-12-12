<?php declare(strict_types=1);

namespace SnapAdmin\Administration\Controller;

use Doctrine\DBAL\Connection;
use SnapAdmin\Administration\Framework\Routing\KnownIps\KnownIpsCollectorInterface;
use SnapAdmin\Core\Defaults;
use SnapAdmin\Core\DevOps\Environment\EnvironmentHelper;
use SnapAdmin\Core\Framework\Adapter\Twig\TemplateFinder;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\DefinitionInstanceRegistry;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\AllowHtml;
use SnapAdmin\Core\Framework\Feature;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Routing\RoutingException;
use SnapAdmin\Core\Framework\Util\HtmlSanitizer;
use SnapAdmin\Core\PlatformRequest;
use SnapAdmin\Core\System\SystemConfig\SystemConfigService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[Route(defaults: ['_routeScope' => ['administration']])]
#[Package('administration')]
class AdministrationController extends AbstractController
{

    /**
     * @param array<int, int> $supportedApiVersions
     * @internal
     *
     */
    public function __construct(
        private readonly TemplateFinder             $finder,
        private readonly array                      $supportedApiVersions,
        private readonly KnownIpsCollectorInterface $knownIpsCollector,
        private readonly Connection                 $connection,
        private readonly EventDispatcherInterface   $eventDispatcher,
        private readonly string                     $snapCoreDir,
        private readonly HtmlSanitizer              $htmlSanitizer,
        private readonly DefinitionInstanceRegistry $definitionInstanceRegistry,
        ParameterBagInterface                       $params,
        private readonly SystemConfigService        $systemConfigService
    )
    {

    }

    #[Route(path: '/%snap_administration.path_name%', name: 'administration.index', defaults: ['auth_required' => false], methods: ['GET'])]
    public function index(Request $request, Context $context): Response
    {
        $template = $this->finder->find('@Administration/administration/index.html.twig');
        return $this->render($template, [
            'features' => Feature::getAll(),
            'systemLanguageId' => Defaults::LANGUAGE_SYSTEM,
            'defaultLanguageIds' => [Defaults::LANGUAGE_SYSTEM],
            'disableExtensions' => EnvironmentHelper::getVariable('DISABLE_EXTENSIONS', false),
            'liveVersionId' => Defaults::LIVE_VERSION,
            'apiVersion' => $this->getLatestApiVersion(),
            'cspNonce' => $request->attributes->get(PlatformRequest::ATTRIBUTE_CSP_NONCE),
        ]);
    }

    #[Route(path: '/api/_admin/known-ips', name: 'api.admin.known-ips', methods: ['GET'])]
    public function knownIps(Request $request): Response
    {
        $ips = [];

        foreach ($this->knownIpsCollector->collectIps($request) as $ip => $name) {
            $ips[] = [
                'name' => $name,
                'value' => $ip,
            ];
        }

        return new JsonResponse(['ips' => $ips]);
    }

    #[Route(path: '/api/_admin/sanitize-html', name: 'api.admin.sanitize-html', methods: ['POST'])]
    public function sanitizeHtml(Request $request, Context $context): JsonResponse
    {
        if (!$request->request->has('html')) {
            throw RoutingException::missingRequestParameter('html');
        }

        $html = (string)$request->request->get('html');
        $field = (string)$request->request->get('field');

        if ($field === '') {
            return new JsonResponse(
                ['preview' => $this->htmlSanitizer->sanitize($html)]
            );
        }

        [$entityName, $propertyName] = explode('.', $field);
        $property = $this->definitionInstanceRegistry->getByEntityName($entityName)->getField($propertyName);

        if ($property === null) {
            throw RoutingException::invalidRequestParameter($field);
        }

        $flag = $property->getFlag(AllowHtml::class);

        if ($flag === null) {
            return new JsonResponse(
                ['preview' => strip_tags($html)]
            );
        }

        if ($flag instanceof AllowHtml && !$flag->isSanitized()) {
            return new JsonResponse(
                ['preview' => $html]
            );
        }

        return new JsonResponse(
            ['preview' => $this->htmlSanitizer->sanitize($html, [], false, $field)]
        );
    }

    private function getLatestApiVersion(): ?int
    {
        $sortedSupportedApiVersions = array_values($this->supportedApiVersions);

        usort($sortedSupportedApiVersions, fn(int $version1, int $version2) => \version_compare((string)$version1, (string)$version2));

        return array_pop($sortedSupportedApiVersions);
    }
}
