<?php


namespace Boilerplate\Application\Example\Queries\GetAllQuery;


use Boilerplate\Infrastructure\Bus\Abstracts\Validator;

class GetAllQueryValidator extends Validator
{
    public function rules(): array
    {
        return [
            'id' => 'required'
        ];
    }
}