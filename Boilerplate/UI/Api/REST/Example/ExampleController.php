<?php declare(strict_types=1);

namespace Boilerplate\UI\Api\REST\Example;

use App\Http\Controllers\Controller;
use Boilerplate\Application\Example\Queries\GetAllQuery\GetAllQuery;
use Boilerplate\Infrastructure\Bus\QueryBus;
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
        //map request to query

        $response = $this->queryBus->query((GetAllQuery::fromArray($request->all())));

        //validate incoming request -> done in middleware

        //Make the CQRS request

        //Pass request to bus or mediator

        //Return response

        dd($response);

        return response($response, $code = 200);
    }
}