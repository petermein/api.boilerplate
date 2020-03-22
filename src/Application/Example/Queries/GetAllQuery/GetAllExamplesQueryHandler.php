<?php


namespace Api\Application\Example\Queries\GetAllQuery;


use Api\Application\Example\Models\ExampleListDto;
use Api\Application\Example\Repositories\ExampleRepository;
use Api\Infrastructure\Bus\Interfaces\HandlerInterface;

class GetAllExamplesQueryHandler implements HandlerInterface
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


        $dto = new ExampleListDto();
        $dto->id = $getAllQuery->id;

        return $dto;
    }
}