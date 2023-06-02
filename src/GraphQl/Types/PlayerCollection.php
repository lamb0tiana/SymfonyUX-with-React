<?php

namespace App\GraphQl\Types;

use ApiPlatform\GraphQl\Type\Definition\TypeInterface;
use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\ObjectType;

class PlayerCollection extends ObjectType implements TypeInterface
{
    public function __construct(private PlayerRepository $repository, private PlayerItemType $itemType, private PlayerRepository $playerRepository)
    {

        $config = [
            'fields' => [
                'players' => ['type' => ListOfType::listOf($this->itemType),             'resolve' => function ($a) {
                    return [['name' => 'ok', 'slug' => 'ok']];
                    $a = new ArrayCollection([$this->playerRepository->find(2)]);
                    return $a;
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
