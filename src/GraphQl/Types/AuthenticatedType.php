<?php

namespace App\GraphQl\Types;

use ApiPlatform\GraphQl\Type\Definition\TypeInterface;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class AuthenticatedType extends ObjectType implements TypeInterface
{
    public function __construct()
    {
        $this->name = 'AuthenticatedType';
        $config = [
            // Note: 'name' is not needed in this form:
            // it will be inferred from class name by omitting namespace and dropping "Type" suffix
            'fields' => [
               'token' => Type::nonNull(Type::string()),
//                'resolve' => fn() : string => 'from AuthenticatedType'
            ]
        ];
        parent::__construct($config);
    }

    public function getName(): string
    {
        return $this->name;
    }
}
