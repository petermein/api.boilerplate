<?php

declare(strict_types=1);


namespace Api\Presentation\Api\REST;

use Api\Common\Bus\Buses\Bus;
use Api\Common\Bus\Interfaces\RequestInterface;

abstract class BaseController
{
    /**
     * @var Bus
     */
    protected $bus;

    /**
     * ExampleController constructor.
     */
    public function __construct()
    {
        $this->bus = app(Bus::class);
    }

    /**
     * @param RequestInterface $abstract
     * @return mixed
     */
    final public function send(RequestInterface $abstract)
    {
        return $this->bus->send($abstract);
    }
}
