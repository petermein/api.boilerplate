<?php

declare(strict_types=1);

namespace Api\Presentation\Api\REST;

use Api\Application\Auth\Models\User;
use Api\Common\Bus\Buses\Bus;
use Api\Common\Bus\Interfaces\RequestInterface;
use Api\Common\OpenApi\Contracts\DescribableObject;
use Api\Common\OpenApi\Contracts\HasDescription;
use Api\Common\OpenApi\Contracts\HasSummary;
use Api\Common\OpenApi\Traits\DescribesObjectTrait;
use Api\Common\OpenApi\Traits\HasDescriptionTrait;
use Api\Common\OpenApi\Traits\HasSummaryTrait;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as LumenController;

abstract class RESTController extends LumenController implements HasDescription, HasSummary
{
    use HasDescriptionTrait;
    use HasSummaryTrait;

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
