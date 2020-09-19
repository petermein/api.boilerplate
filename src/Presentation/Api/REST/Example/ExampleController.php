<?php

declare(strict_types=1);

namespace Api\Presentation\Api\REST\Example;

use Api\Application\Example\Models\ExampleListDto;
use Api\Application\Example\Queries\GetAllQuery\GetAllExamplesQuery;
use Api\Presentation\Api\REST\BaseController;
use Illuminate\Http\JsonResponse;
use Psr\Log\LoggerInterface;

/**
 * Class ExampleController
 * @package Api\Presentation\Api\REST\Example
 */
final class ExampleController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
        parent::__construct();
    }

    /**
     * @param GetAllExamplesQuery $request
     *
     * @OA\Get(
     *     path="/example",
     *     tags={"example"},
     *     summary="Get a list of examples",
     *     operationId="getAllExamplesQuery",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/ExampleListDto"),
     *     ),
     *     @OA\Parameter(
     *        name="id", in="path",required=true, @OA\Schema(type="integer")
     *     ),
     * )
     */
    public function getAll(GetAllExamplesQuery $request, LoggerInterface $logger): JsonResponse
    {
        $logger->debug($request->all());

        /** @var ExampleListDto $dto */
        $dto = $this->send($request);

        return response()->json($dto, $code = 200);
    }
}
