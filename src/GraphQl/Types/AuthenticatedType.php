<?php

namespace App\GraphQl\Types;

use ApiPlatform\GraphQl\Type\Definition\TypeInterface;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
class AuthenticatedValue{
    public string $token;
}
class AuthenticatedType extends ObjectType implements TypeInterface
{
    public function __construct()
    {
        $config = [
            'fields' => [
               'token' => Type::nonNull(Type::string())
            ]
        ];
        parent::__construct($config);
    }

    public function getName(): string
    {
        return $this->name;
    }
}
