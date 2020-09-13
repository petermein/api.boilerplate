<?php

declare(strict_types=1);


namespace Api\Infrastructure\Persistence\Repositories\Doctrine;

use Api\Application\Example\Repositories\ExampleRepository as IExampleRepository;
use Api\Domain\Exceptions\ModelNotFoundException;
use Api\Domain\Models\Example;

final class ExampleRepository extends BaseDoctrineRepository implements IExampleRepository
{
    public function find(int $id): Example
    {
        /** @var Example $object */
        $object = $this->genericRepository->find($id);

        if ($object === null) {
            throw ModelNotFoundException::modelNotFound((new Example), $id);
        }

        return $object;
    }

    public function save(Example $example): void
    {
        $this->entityManager->persist($example);
    }
}
