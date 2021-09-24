<?php

declare(strict_types=1);

namespace Api\Presentation\Api\GraphQL\Types;

use Api\Application\Example\Models\ExampleDto;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

final class ExampleType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Example',
        'description' => 'A type',
        'model' => ExampleDto::class
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of the user',
                'alias' => 'id',
            ],
        ];
    }
}
