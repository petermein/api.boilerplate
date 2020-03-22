<?php


namespace Api\Application\System;


use Api\Infrastructure\Bus\Abstracts\ValidatorAbstract;

class GlobalValidator extends ValidatorAbstract
{
    public function rules(): array
    {
        return [
            'id' => 'min:5'
        ];
    }
}