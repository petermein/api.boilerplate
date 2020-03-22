<?php


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