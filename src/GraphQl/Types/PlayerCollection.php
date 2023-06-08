<?php

namespace App\GraphQl\Types;

use ApiPlatform\GraphQl\Type\Definition\TypeInterface;
use App\Entity\Player;
use App\Repository\PlayerRepository;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class PlayerCollection extends ObjectType implements TypeInterface
{
    public function __construct(private PlayerItemType $itemType, private PlayerRepository $playerRepository)
    {
        $config = [
            'fields' => [
                'players' => [
                    'type' => Type::listOf($this->itemType),
                    'resolve' => function (array $players) {
                        $playerIds = array_map(fn (Player $p) =>  $p->getId(), $players);
                        return $this->playerRepository->getPlayers($playerIds);
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
