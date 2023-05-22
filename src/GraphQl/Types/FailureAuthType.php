<?php

namespace App\GraphQl\Types;

use ApiPlatform\GraphQl\Type\Definition\TypeInterface;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class FailureAuthType extends ObjectType implements TypeInterface
{
    public function __construct()
    {
        $config = [
            'name' => 'FailureAuthType',
            // Note: 'name' is not needed in this form:
            // it will be inferred from class name by omitting namespace and dropping "Type" suffix
            'fields' => [
                'error' => Type::nonNull(Type::string())
            ],

        ];
        parent::__construct($config);
    }

    public function getName(): string
    {
        return 'FailureAuthType';
    }

}
