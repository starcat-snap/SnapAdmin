<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\User\Validator;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\Validation\PreWriteValidationEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserNameAndNickNameValidator implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            PreWriteValidationEvent::class => 'preValidate',
        ];
    }

    public function preValidate(PreWriteValidationEvent $event): void{

    }
}
