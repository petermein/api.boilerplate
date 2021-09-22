<?php

declare(strict_types=1);

namespace Api\Application\Example\Queries\GetAllQuery;

use Api\Application\Example\Models\ExampleListDto;
use Api\Application\Example\Repositories\ExampleRepository;
use Api\Common\Bus\Interfaces\HandlerInterface;

/**
 * Class GetAllExamplesQueryHandler
 * @package Api\Application\Example\Queries\GetAllQuery
 */
final class GetAllExamplesQueryHandler implements HandlerInterface
{
    /**
     * @var ExampleRepository
     */
    private $exampleRepository;

    /**
     * GetAllExamplesQueryHandler constructor.
     * @param ExampleRepository $exampleRepository
     */
    public function __construct(ExampleRepository $exampleRepository)
    {
        $this->exampleRepository = $exampleRepository;
    }

    /**
     * @param GetAllExamplesQuery $getAllQuery
     * @return ExampleListDto
     */
    public function __invoke(GetAllExamplesQuery $getAllQuery): ExampleListDto
    {
        $example = $this->exampleRepository->find($getAllQuery->id);

        $dto = new ExampleListDto([
            'id' => $example->id
        ]);
        dd(1);

        return $dto;
    }
}
