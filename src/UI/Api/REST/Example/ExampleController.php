<?php declare(strict_types=1);

namespace Api\UI\Api\REST\Example;

use App\Http\Controllers\Controller;
use Api\Application\Example\Models\ExampleListDto;
use Api\Application\Example\Queries\GetAllQuery\GetAllExamplesQuery;
use Api\Infrastructure\Bus\Buses\QueryBus;
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
     * @OA\Get(
     *   path="/api/v1/example",
     *   summary="list examples",
     *   @OA\Response(
     *     response=200,
     *     description="A list with examples"
     *   ),
     *   @OA\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function getAll(Request $request)
    {
        /** @var ExampleListDto $response */
        $response = $this->queryBus->query((GetAllExamplesQuery::fromArray($request->all())));

        return response()->json($response, $code = 200);
    }
}