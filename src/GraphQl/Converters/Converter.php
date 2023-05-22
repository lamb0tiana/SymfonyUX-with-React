<?php

namespace App\GraphQl\Converters;

use ApiPlatform\GraphQl\Type\TypeConverterInterface;
use ApiPlatform\Metadata\GraphQl\Operation;
use App\GraphQl\Types\AuthenticatedType;
use App\GraphQl\Types\AuthUnionType;
use GraphQL\Type\Definition\Type as GraphQLType;
use Symfony\Component\PropertyInfo\Type;

final class Converter implements TypeConverterInterface
{
    public function __construct(private TypeConverterInterface $defaultTypeConverter)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function resolveType(string $type): ?GraphQLType
    {
        return $this->defaultTypeConverter->resolveType($type);
    }

    public function convertType(Type $type, bool $input, Operation $rootOperation, string $resourceClass, string $rootResource, ?string $property, int $depth): GraphQLType|string|null
    {
        if ($property === "error") {
            return new AuthUnionType();
        }
        return $this->defaultTypeConverter->convertType($type, $input, $rootOperation, $resourceClass, $rootResource, $property, $depth);
    }
}
