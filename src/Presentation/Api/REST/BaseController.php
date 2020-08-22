<?php

declare(strict_types=1);


namespace Api\Presentation\Api\REST;

use Api\Common\Bus\Buses\QueryBus;
use Api\Common\Bus\Interfaces\QueryInterface;

abstract class BaseController
{
    /**
     * @var QueryBus
     */
    protected $queryBus;

    /**
     * ExampleController constructor.
     */
    public function __construct()
    {
        $this->queryBus = app(QueryBus::class);
    }

    final public function query(QueryInterface $abstract)
    {
        return $this->queryBus->query($abstract);
    }
}
