<?php


namespace Api\Presentation\Api\GraphQL\Queries;

use Api\Application\Example\Queries\GetAllQuery\GetAllExamplesQuery;
use Api\Infrastructure\Bus\Buses\QueryBus;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class ExampleQuery extends Query
{
    protected $attributes = [
        'name' => 'Example query'
    ];
    /**
     * @var QueryBus
     */
    private QueryBus $bus;

    /**
     * ExampleQuery constructor.
     * @param QueryBus $bus
     */
    public function __construct(QueryBus $bus)
    {

        $this->bus = $bus;
    }

    public function type(): Type
    {
        return GraphQL::type('example');
    }

    public function args(): array
    {
        return [
            'id' => ['name' => 'id', 'type' => Type::nonNull(Type::int())],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $query = new GetAllExamplesQuery();
        $query->id = $args['id'];

        return $this->bus->query($query);
    }
}