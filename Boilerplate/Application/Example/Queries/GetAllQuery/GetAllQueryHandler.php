<?php


namespace Boilerplate\Application\Example\Queries\GetAllQuery;


use Boilerplate\Infrastructure\Bus\Interfaces\Handler;

class GetAllQueryHandler implements Handler
{
    /**
     * @param GetAllQuery $getAllQuery
     * @return ExampleListDto
     */
    public function __invoke(GetAllQuery $getAllQuery): ExampleListDto
    {
        dump('handler');

        return new ExampleListDto();
    }
}