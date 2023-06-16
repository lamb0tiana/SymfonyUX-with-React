<?php

namespace App\GraphQl\Types;

use ApiPlatform\GraphQl\Type\Definition\TypeInterface;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class PlayerCollection extends ObjectType implements TypeInterface
{
    public function __construct(private PlayerItemType $itemType)
    {
        $config = [
            'fields' => [
                'players' => [
                    'type' => Type::listOf($this->itemType),
                    'resolve' => fn (array $players) => $players]
            ],
        ];
        parent::__construct($config);
    }

    public function getName(): string
    {
        return $this->name;
    }
}
