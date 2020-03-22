<?php


namespace Api\Application\Example\Queries\GetAllQuery;


use Api\Infrastructure\Bus\Abstracts\ValidatorAbstract;

class GetAllExamplesQueryValidatorBound extends ValidatorAbstract
{
    public function rules(): array
    {
        return [
            'id' => 'min:1'
        ];
    }
}