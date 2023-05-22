<?php

namespace App\Resolver;

use ApiPlatform\GraphQl\Resolver\MutationResolverInterface;
use App\ApiResource\AppAuthentication;
use App\GraphQl\Types\AuthenticatedType;
use App\GraphQl\Types\AuthUnionType;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Guard\JWTTokenAuthenticator;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class Authentication implements MutationResolverInterface
{
    public function __construct(private JWTTokenManagerInterface $tokenManager,private UserPasswordHasherInterface $hasher)
    {

    }

    /** @var AppAuthentication $item */
    public function __invoke(?object $item, array $context): object
    {

    $item->token = 'mytoken';


        return $item;
    }
}
