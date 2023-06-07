<?php

namespace App\GraphQl\Types;

use ApiPlatform\GraphQl\Type\Definition\TypeInterface;
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
                    'args' => ["inCurrentTeam" =>[ 'type' => Type::boolean()]],
                    'resolve' => function (array $team, $args) {
                        $inCurrentTeam = null;
                        if ($args) {
                            ['inCurrentTeam' => $inCurrentTeam] = $args;
                        }
                        $playerIds = array_map(fn ($p) =>  $p["#itemIdentifiers"]["id"], $team);
                        return $this->playerRepository->getPlayers($inCurrentTeam, $playerIds);
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
