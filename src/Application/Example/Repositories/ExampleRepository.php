<?php


namespace Api\Application\Example\Repositories;


use Api\Domain\Models\Example;

interface ExampleRepository
{
    public function find(int $id): ?Example;
}