<?php

namespace App\GraphQl\Types;

use ApiPlatform\GraphQl\Type\Definition\TypeInterface;
use App\Repository\PlayerRepository;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;


class PlayerCollection extends ObjectType implements TypeInterface
{
    public function __construct(private PlayerItemType $itemType, private PlayerRepository $playerRepository)
    {

        $config = [
            'fields' => [
                'players' => ['type' => Type::listOf($this->itemType),            'resolve' => function ($a) {
                    return [['name' => 'ok', 'id' => 2]];
                }]
            ],
        ];
        parent::__construct($config);
    }

    public function getName(): string
    {
        return $this->name;
    }

}
