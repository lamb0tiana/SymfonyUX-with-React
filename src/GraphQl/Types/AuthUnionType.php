<?php

namespace App\GraphQl\Types;

use ApiPlatform\GraphQl\Type\Definition\TypeInterface;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\UnionType;

class AuthUnionType extends UnionType implements TypeInterface
{
    public function __construct()
    {
        $this->name = 'UnionAuthType';
        $config = [
            'name' => 'UnionAuthType',
            'types' => [
                Type::getNamedType(new AuthenticatedType()),
                Type::getNamedType(new FailureAuthType())
            ]
        ];
        parent::__construct($config);
    }

    public function getName(): string
    {
        return  $this->name;
    }

}
