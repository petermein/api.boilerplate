<?php

declare(strict_types=1);


namespace Api\Infrastructure\Persistence\Repositories\Doctrine;


use Api\Application\Example\Repositories\ExampleRepository as IExampleRepository;
use Api\Domain\Models\Example;

class ExampleRepository extends BaseDoctrineRepository implements IExampleRepository
{
    public function find(int $id): ?Example
    {
        $object = $this->genericRepository->find($id);

        return $object;
    }

    public function save(Example $example): void
    {
        $this->entityManager->persist($example);
    }
}