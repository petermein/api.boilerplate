<?php

declare(strict_types = 1);



namespace Api\Presentation\Api\REST;


use Api\Infrastructure\Bus\Abstracts\QueryAbstract;
use Api\Infrastructure\Bus\Buses\QueryBus;

class BaseController
{
    /**
     * @var QueryBus
     */
    protected $queryBus;

    /**
     * ExampleController constructor.
     * @param QueryBus $queryBus
     */
    public function __construct()
    {
        $this->queryBus = app(QueryBus::class);
    }

    public function query(QueryAbstract $abstract)
    {
        return $this->queryBus->query($abstract);
    }
}