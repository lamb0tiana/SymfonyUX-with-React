<?php

namespace App\GraphQl\Types;

use ApiPlatform\GraphQl\Type\Definition\TypeInterface;
use App\Entity\Player;
use App\Repository\PlayerRepository;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Symfony\Component\Serializer\SerializerInterface;

class PlayerCollection extends ObjectType implements TypeInterface
{
    public function __construct(private PlayerItemType $itemType, private PlayerRepository $playerRepository, private SerializerInterface $serializer)
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
