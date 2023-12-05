<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DependencyInjection\CompilerPass;

use SnapAdmin\Core\Content\Flow\Dispatching\Storer\ConfirmUrlStorer;
use SnapAdmin\Core\Content\Flow\Dispatching\Storer\ContactFormDataStorer;
use SnapAdmin\Core\Content\Flow\Dispatching\Storer\ContentsStorer;
use SnapAdmin\Core\Content\Flow\Dispatching\Storer\ContextTokenStorer;
use SnapAdmin\Core\Content\Flow\Dispatching\Storer\DataStorer;
use SnapAdmin\Core\Content\Flow\Dispatching\Storer\EmailStorer;
use SnapAdmin\Core\Content\Flow\Dispatching\Storer\NameStorer;
use SnapAdmin\Core\Content\Flow\Dispatching\Storer\RecipientsStorer;
use SnapAdmin\Core\Content\Flow\Dispatching\Storer\ResetUrlStorer;
use SnapAdmin\Core\Content\Flow\Dispatching\Storer\ReviewFormDataStorer;
use SnapAdmin\Core\Content\Flow\Dispatching\Storer\ShopNameStorer;
use SnapAdmin\Core\Content\Flow\Dispatching\Storer\SubjectStorer;
use SnapAdmin\Core\Content\Flow\Dispatching\Storer\TemplateDataStorer;
use SnapAdmin\Core\Content\Flow\Dispatching\Storer\UrlStorer;
use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

#[Package('business-ops')]
class RemoveOldFlowStorerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $deprecated = [
            ResetUrlStorer::class,
            RecipientsStorer::class,
            ContextTokenStorer::class,
            NameStorer::class,
            DataStorer::class,
            ContactFormDataStorer::class,
            ContentsStorer::class,
            ConfirmUrlStorer::class,
            ReviewFormDataStorer::class,
            EmailStorer::class,
            UrlStorer::class,
            TemplateDataStorer::class,
            SubjectStorer::class,
            ShopNameStorer::class,
        ];

        foreach ($deprecated as $serviceId) {
            $this->removeTag($container, $serviceId);
        }
    }

    private function removeTag(ContainerBuilder $container, string $serviceId): void
    {
        if (!$container->hasDefinition($serviceId)) {
            return;
        }

        $definition = $container->getDefinition($serviceId);

        if (!$definition->hasTag('flow.storer')) {
            return;
        }

        $definition->clearTag('flow.storer');
    }
}
