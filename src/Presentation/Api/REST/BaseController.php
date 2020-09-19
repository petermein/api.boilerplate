<?php

declare(strict_types=1);


namespace Api\Presentation\Api\REST;

use Api\Application\Auth\Models\User;
use Api\Common\Bus\Buses\Bus;
use Api\Common\Bus\Interfaces\RequestInterface;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as LumenController;

abstract class BaseController extends LumenController
{
    /**
     * @var Bus
     */
    protected $bus;

    /**
     * @var Request
     */
    protected $request;

    /**
     * ExampleController constructor.
     */
    public function __construct()
    {
        $this->bus = app(Bus::class);
        $this->request = app(Request::class);
    }

    /**
     * @param RequestInterface $abstract
     * @return mixed
     */
    final public function send(RequestInterface $abstract)
    {
        return $this->bus->send($abstract);
    }

    final public function user(): User
    {
        return $this->request->user();
    }
}
