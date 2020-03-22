<?php declare(strict_types=1);

namespace Api\UI\Api\REST\Example;

use Api\Application\Example\Models\ExampleListDto;
use Api\Application\Example\Queries\GetAllQuery\GetAllExamplesQuery;
use Api\Infrastructure\Bus\Buses\QueryBus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExampleController extends Controller
{
    /**
     * @var QueryBus
     */
    private $queryBus;

    /**
     * ExampleController constructor.
     * @param QueryBus $queryBus
     */
    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    /**
     * @Incoming(schema="Api\Application\Example\Queries\GetAllQuery\GetAllExamplesQuery")
     * @Outgoing(code=201, schema="Acme\Model\Message")
     */
    public function getAll(Request $request)
    {
        /** @var ExampleListDto $response */
        $response = $this->queryBus->query((GetAllExamplesQuery::fromArray($request->all())));

        return response()->json($response, $code = 200);
    }
}