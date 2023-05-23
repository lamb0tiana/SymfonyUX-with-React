<?php

namespace App\Resolver;

use ApiPlatform\GraphQl\Resolver\MutationResolverInterface;
use App\ApiResource\AppAuthentication;
use App\ApiResource\Security\FailureAuth;
use App\ApiResource\Security\SuccessAuth;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Guard\JWTTokenAuthenticator;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class Authentication implements MutationResolverInterface
{
    public function __construct(private JWTTokenManagerInterface $tokenManager, private UserPasswordHasherInterface $hasher)
    {
    }

    /** @var AppAuthentication $item */
    public function __invoke(?object $item, array $context): ?object
    {
        $rand = rand(0, 10);
        if ($rand%2 === 0) {
            $value = new FailureAuth();
            $value->error = 'invalid auth';
        } else {
            $value = new SuccessAuth();
            $value->token = uniqid();
        }
        $item->authPayloads = $value;

        return $item;
    }
}
