<?php

namespace App\EventSubscriber;

use App\Entity\TeamManager;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class JWTSubscriber implements EventSubscriberInterface
{
    public function __construct(private NormalizerInterface $normalizer)
    {
    }
    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $data = $event->getData();
        /** @var TeamManager $user */
        $user = $event->getUser();
        $data['email'] = $user->getEmail();
        $data['team'] = $this->normalizer->normalize($user->getTeam(), 'json', ['groups' => ['read']]);
        $event->setData($data);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'lexik_jwt_authentication.on_jwt_created' => 'onJWTCreated',
        ];
    }
}
