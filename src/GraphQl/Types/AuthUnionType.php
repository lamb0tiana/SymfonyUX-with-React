<?php

namespace App\GraphQl\Types;

use ApiPlatform\GraphQl\Type\Definition\TypeInterface;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\UnionType;

class AuthUnionType extends UnionType implements TypeInterface
{
    public function __construct(private AuthenticatedType $authenticatedType, private FailureAuthType $failureAuthType)
    {
        $config = [
            'types' => [
                Type::getNamedType($authenticatedType),
                Type::getNamedType($failureAuthType)
            ],
            'resolveType' => function ($value) use ($failureAuthType, $authenticatedType): ObjectType {
                return  !in_array('token', array_keys($value)) ? $failureAuthType : $authenticatedType;
            },
        ];
        parent::__construct($config);
    }

    public function getName(): string
    {
        return  $this->name;
    }

}
