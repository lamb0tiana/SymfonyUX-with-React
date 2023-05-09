<?php

namespace App\GraphQl\Input;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

class CreatePlayerInput extends InputObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'CreateUserInput',
            'description' => 'Input type for creating a user',
            'fields' => [
                'firstName' => Type::nonNull(Type::string()),
                'lastName' => Type::nonNull(Type::string()),
                'email' => Type::nonNull(Type::string()),
                'password' => Type::nonNull(Type::string()),
            ],
        ];
        parent::__construct($config);
    }
}
