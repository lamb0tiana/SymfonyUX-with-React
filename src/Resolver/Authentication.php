<?php

namespace App\Resolver;

use ApiPlatform\GraphQl\Resolver\MutationResolverInterface;
use App\ApiResource\AppAuthentication;
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


        ["args" =>["input" => ["email" => $email, "password" => $password]]] = $context;


//        $this->hasher->isPasswordValid($user, $plainPassword);
        return $item;
    }
}
