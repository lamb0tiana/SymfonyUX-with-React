<?php

namespace App\GraphQl\Types;

use ApiPlatform\GraphQl\Type\Definition\TypeInterface;
use GraphQL\Error\Error;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\UnionType;
use GraphQL\Utils\Utils;

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
    /**
     * {@inheritdoc}
     */
    public function parseValue($value)
    {
        if (!\is_string($value)) {
            throw new Error(sprintf('DateTime cannot represent non string value: %s', Utils::printSafeJson($value)));
        }

        if (false === \DateTime::createFromFormat(\DateTime::ATOM, $value)) {
            throw new Error(sprintf('DateTime cannot represent non date value: %s', Utils::printSafeJson($value)));
        }

        // Will be denormalized into a \DateTime.
        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function parseLiteral($valueNode, ?array $variables = null)
    {
        if ($valueNode instanceof StringValueNode && false !== \DateTime::createFromFormat(\DateTime::ATOM, $valueNode->value)) {
            return $valueNode->value;
        }

        // Intentionally without message, as all information already in wrapped Exception
        throw new \Exception();
    }
}
