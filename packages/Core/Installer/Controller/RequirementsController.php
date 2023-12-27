<?php declare(strict_types=1);

namespace SnapAdmin\Core\Installer\Controller;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Installer\Requirements\RequirementsValidatorInterface;
use SnapAdmin\Core\Installer\Requirements\Struct\RequirementsCheckCollection;
use SnapAdmin\Core\Maintenance\System\Service\JwtCertificateGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @internal
 */
#[Package('core')]
class RequirementsController extends InstallerController
{
    private readonly string $jwtDir;

    /**
     * @param iterable|RequirementsValidatorInterface[] $validators
     */
    public function __construct(
        private readonly iterable $validators,
        private readonly JwtCertificateGenerator $jwtCertificateGenerator,
        string $projectDir
    ) {
        $this->jwtDir = $projectDir . '/config/jwt';
    }

    #[Route(path: '/installer/requirements', name: 'installer.requirements', methods: ['GET', 'POST'])]
    public function requirements(Request $request): Response
    {
        $checks = new RequirementsCheckCollection();

        foreach ($this->validators as $validator) {
            $checks = $validator->validateRequirements($checks);
        }

        if ($request->isMethod('POST') && !$checks->hasError()) {
            // The JWT dir exist and is writable, so we generate a new key pair
            $this->jwtCertificateGenerator->generate(
                $this->jwtDir . '/private.pem',
                $this->jwtDir . '/public.pem'
            );

            return $this->redirectToRoute('installer.database-configuration');
        }

        return $this->renderInstaller('@Installer/installer/requirements.html.twig', ['requirementChecks' => $checks]);
    }
}
