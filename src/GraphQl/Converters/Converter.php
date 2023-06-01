<?php

namespace App\GraphQl\Converters;

use ApiPlatform\GraphQl\Type\TypeConverterInterface;
use ApiPlatform\Metadata\GraphQl\Operation;
use App\GraphQl\Types\AuthUnionType;
use App\GraphQl\Types\PlayerCollection;
use GraphQL\Type\Definition\Type as GraphQLType;
use Symfony\Component\PropertyInfo\Type;

final class Converter implements TypeConverterInterface
{
    public function __construct(private TypeConverterInterface $defaultTypeConverter, private AuthUnionType $authUnionType, private PlayerCollection $playerCollection)
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
        if ($property === "authPayloads") {
            return $this->authUnionType;
        }
        if($resourceClass === PlayerCollection::class) {
            return $this->playerCollection;
        }
        return $this->defaultTypeConverter->convertType($type, $input, $rootOperation, $resourceClass, $rootResource, $property, $depth);
    }
}
