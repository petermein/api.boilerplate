<?php


namespace Api\Infrastructure\Persistence\Repositories\Doctrine;


use Api\Application\Example\Repositories\ExampleRepository as IExampleRepository;
use Api\Domain\Models\Example;

class ExampleRepository implements IExampleRepository
{

    public function find(int $id): ?Example
    {
        // get from database

        // map to domain model
    }
}