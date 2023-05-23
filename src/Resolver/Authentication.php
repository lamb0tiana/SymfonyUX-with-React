<?php

namespace App\Resolver;

use ApiPlatform\GraphQl\Resolver\MutationResolverInterface;
use App\ApiResource\AppAuthentication;
use App\Security\Authentication as SecurityAuth;

class Authentication implements MutationResolverInterface
{
    public function __construct(private SecurityAuth $authentication)
    {
    }

    /** @var AppAuthentication $item */
    public function __invoke(?object $item, array $context): ?object
    {
        ["email" => $email, "password" => $password] = $context['args']['input'];

        $item->authPayloads = $this->authentication->auth($password, $email);

        return $item;
    }
}
