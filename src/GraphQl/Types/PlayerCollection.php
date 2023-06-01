<?php

namespace App\GraphQl\Types;

use ApiPlatform\GraphQl\Type\Definition\TypeInterface;
use App\Repository\PlayerRepository;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Utils\BuildSchema;

class PlayerCollection extends ObjectType implements TypeInterface
{
    public function __construct(private string $dir, private PlayerRepository $repository)
    {
        $out = file_get_contents("$dir/public/schema.graphql");
        $schema = BuildSchema::build($out);
        $type =  $schema->getType('Player');
        $fields = $type->getFields();
        unset($fields['playerTeams']);
        $oType = new ObjectType(['fields' => $fields, 'name' => 'player', 'args' => ['name' => 'filter', 'type' => 'string']]);
        $config = [
            'fields' => [
                'players' => ['type' => ListOfType::listOf($oType), 'argss' => ['name' => 'filter', 'type' => 'string'], 'resolve' => function ($a) {
                    $ids = array_map(function ($x) {
                        return $x['#itemIdentifiers']['id'];
                    }, $a);
                    return [$this->repository->find($ids[0])];
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
