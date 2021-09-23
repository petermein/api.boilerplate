<?php

declare(strict_types=1);

namespace Api\Application\Example\Repositories;

use Api\Domain\Models\Example;

interface ExampleRepository
{
    /**
     * @param int $id
     * @return Example
     * @throws \Api\Domain\Exceptions\ModelNotFoundException
     */
    public function find(int $id): Example;

    public function save(Example $example): void;
}
