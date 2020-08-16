<?php declare(strict_types=1);

namespace Api\UI\Api\REST\Example;

use Api\Application\Example\Models\ExampleListDto;
use Api\Application\Example\Queries\GetAllQuery\GetAllExamplesQuery;
use Api\UI\Api\REST\BaseController;

class ExampleController extends BaseController
{


    /**
     * @param GetAllExamplesQuery $request
     * @Incoming(schema="Api\Application\Example\Queries\GetAllQuery\GetAllExamplesQuery")
     * @Outgoing(code=201, schema="Acme\Model\Message")
     */
    public function getAll(GetAllExamplesQuery $request)
    {
        /** @var ExampleListDto $response */
        $response = $this->query($request);

        return response()->json($response, $code = 200);
    }
}