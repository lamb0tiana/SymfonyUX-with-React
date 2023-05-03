<?php

namespace App\EventSubscriber;

use App\Entity\TeamManager;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class JWTSubscriber implements EventSubscriberInterface
{
    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $data = $event->getData();
        /** @var TeamManager $user */
        $user = $event->getUser();
        $data['email'] = $user->getEmail();
        $data['teamId'] = $user->getTeam()?->getId();
        $event->setData($data);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'lexik_jwt_authentication.on_jwt_created' => 'onJWTCreated',
        ];
    }
}
