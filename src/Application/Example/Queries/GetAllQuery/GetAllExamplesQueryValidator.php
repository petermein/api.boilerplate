<?php


namespace Api\Application\Example\Queries\GetAllQuery;


use Api\Infrastructure\Bus\Abstracts\ValidatorAbstract;

class GetAllExamplesQueryValidator extends ValidatorAbstract
{
    public function rules(): array
    {
        return [
            'id' => 'required'
        ];
    }
}