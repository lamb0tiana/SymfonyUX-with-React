<?php


namespace App\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GraphQl\Mutation;
use App\ApiResource\Security\FailureAuth;
use App\ApiResource\Security\SuccessAuth;
use App\Resolver\MyResolver;
use App\Resolver\Output;
use App\Resolver\Authentication as AuthResolver;
const    ARGS= [
    'email' => ['type' => 'String!', 'description' => 'User identifiant'],
    'password' => ['type' => 'String!']
];
#[ApiResource(
    operations: [],
    graphQlOperations: [
    new Mutation(name: "_", args:   ARGS, resolver: AuthResolver::class)
])]
class AppAuthentication
{
    #[ApiProperty(identifier: true, iris:"https://schema.org/identifier" , readable: false)]
    public int $id =0;

    public SuccessAuth|FailureAuth $authPayloads;

}