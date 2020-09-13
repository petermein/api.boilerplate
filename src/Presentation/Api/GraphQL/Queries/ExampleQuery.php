<?php

declare(strict_types=1);


namespace Api\Presentation\Api\GraphQL\Queries;

use Api\Application\Example\Queries\GetAllQuery\GetAllExamplesQuery;
use Api\Common\Bus\Buses\Bus;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

final class ExampleQuery extends Query
{
    protected $attributes = [
        'name' => 'Example query'
    ];
    /**
     * @var Bus
     */
    private Bus $bus;

    /**
     * ExampleQuery constructor.
     * @param Bus $bus
     */
    public function __construct(Bus $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @return Type
     */
    public function type(): Type
    {
        return GraphQL::type('example');
    }

    /**
     * @return array[]
     */
    public function args(): array
    {
        return [
            'id' => ['name' => 'id', 'type' => Type::nonNull(Type::int())],
        ];
    }

    /**
     * @param $root
     * @param $args
     * @param $context
     * @param ResolveInfo $resolveInfo
     * @param Closure $getSelectFields
     * @return mixed
     */
    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $query = new GetAllExamplesQuery();
        $query->id = $args['id'];

        return $this->bus->send($query);
    }
}
