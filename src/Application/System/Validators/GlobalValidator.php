<?php

declare(strict_types = 1);



namespace Api\Application\System\Validators;


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