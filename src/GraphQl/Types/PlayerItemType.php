<?php

namespace App\GraphQl\Types;

use ApiPlatform\GraphQl\Type\Definition\TypeInterface;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Utils\BuildSchema;

class PlayerItemType extends ObjectType implements TypeInterface
{
    public function __construct(private string $dir)
    {
        $out = file_get_contents("$dir/public/schema.graphql");
        $schema = BuildSchema::build($out);
        $type =  $schema->getType('Player');
        $fields = $type->getFields();
        unset($fields['playerTeams'], $fields['_id'], $fields['currentTeam']);

        $config = [
            'fields' => $fields,

        ];
        parent::__construct($config);
    }

    public function getName(): string
    {
        return $this->name;
    }

}
