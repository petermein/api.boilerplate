<?php


namespace Boilerplate\Application\Example\Queries\GetAllQuery;


use Symfony\Component\Messenger\HandleTrait;

class GetAllQueryHandler
{
    public function __invoke(GetAllQuery $getAllQuery)
    {
        return new ExampleListDto();
    }
}