<?php declare(strict_types=1);

namespace SnapAdmin\Core\Installer\Controller;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Installer\Configuration\AdminConfigurationService;
use SnapAdmin\Core\Installer\Configuration\EnvConfigWriter;
use SnapAdmin\Core\Maintenance\System\Service\DatabaseConnectionFactory;
use SnapAdmin\Core\Maintenance\System\Struct\DatabaseConnectionInformation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @internal
 *
 * @phpstan-type AdminUser array{email: string, username: string, firstName: string, lastName: string, password: string}
 */
#[Package('core')]
class SystemConfigurationController extends InstallerController
{
    /**
     * @param array<string, string> $supportedLanguages
     * @param list<string> $supportedCurrencies
     */
    public function __construct(
        private readonly DatabaseConnectionFactory $connectionFactory,
        private readonly EnvConfigWriter $envConfigWriter,
        private readonly AdminConfigurationService $adminConfigurationService,
        private readonly array $supportedLanguages,
    ) {
    }

    #[Route(path: '/installer/configuration', name: 'installer.configuration', methods: ['GET', 'POST'])]
    public function shopConfiguration(Request $request): Response
    {
        $session = $request->getSession();
        /** @var DatabaseConnectionInformation|null $connectionInfo */
        $connectionInfo = $session->get(DatabaseConnectionInformation::class);

        if (!$connectionInfo) {
            return $this->redirectToRoute('installer.database-configuration');
        }

        $connection = $this->connectionFactory->getConnection($connectionInfo);

        $error = null;

        if ($request->getMethod() === 'POST') {
            $adminUser = [
                'email' => (string) $request->request->get('config_admin_email'),
                'username' => (string) $request->request->get('config_admin_username'),
                'name' => (string) $request->request->get('config_admin_firstName'),
                'phone' => (string) $request->request->get('config_admin_lastName'),
                'password' => (string) $request->request->get('config_admin_password'),
                'locale' => $this->supportedLanguages[$request->attributes->get('_locale')],
            ];

            $schema = 'http';
            // This is for supporting Apache 2.2
            if (\array_key_exists('HTTPS', $_SERVER) && mb_strtolower((string) $_SERVER['HTTPS']) === 'on') {
                $schema = 'https';
            }
            if (\array_key_exists('REQUEST_SCHEME', $_SERVER)) {
                $schema = $_SERVER['REQUEST_SCHEME'];
            }

            try {
                $this->envConfigWriter->writeConfig($connectionInfo);

                // create admin user first, if there is a validation error we don't need to update shop
                // and create sales channel
                $this->adminConfigurationService->createAdmin($adminUser, $connection);

                $session->remove(DatabaseConnectionInformation::class);
                $session->set('ADMIN_USER', $adminUser);

                return $this->redirectToRoute('installer.finish');
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        return $this->renderInstaller(
            '@Installer/installer/admin-configuration.html.twig',
            [
                'error' => $error,
                'languageIsos' => $this->supportedLanguages,
                'parameters' => $request->request->all(),
            ]
        );
    }
}
