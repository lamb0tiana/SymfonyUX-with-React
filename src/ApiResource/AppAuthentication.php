<?php


namespace App\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GraphQl\Mutation;
use App\GraphQl\Types\AuthenticatedType;
use App\GraphQl\Types\AuthUnionType;
use App\Resolver\MyResolver;
use App\Resolver\Output;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Resolver\Authentication as AuthResolver;
const    ARGS= [
    'email' => ['type' => 'String!', 'description' => 'User identifiant'],
    'password' => ['type' => 'String!']
];
#[ApiResource(
    graphQlOperations: [
    new CustomMutation(name: "_", args:   ARGS, resolver: AuthResolver::class)
])]
class AppAuthentication
{
    #[ApiProperty(identifier: true, iris:"https://schema.org/identifier" , readable: false)]
    public int $id =0;

    public AuthUnionType $authPayloads;

}