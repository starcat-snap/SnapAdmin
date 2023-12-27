<?php declare(strict_types=1);

namespace SnapAdmin\Core\Installer\Controller;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Installer\Finish\Notifier;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @internal
 */
#[Package('core')]
class SelectLanguagesController extends InstallerController
{
    public function __construct(private readonly Notifier $notifier)
    {
    }

    #[Route(path: '/installer', name: 'installer.language-selection', methods: ['GET'])]
    public function languageSelection(): Response
    {
        $this->notifier->doTrackEvent(Notifier::EVENT_INSTALL_STARTED);

        return $this->renderInstaller('@Installer/installer/language-selection.html.twig');
    }
}
