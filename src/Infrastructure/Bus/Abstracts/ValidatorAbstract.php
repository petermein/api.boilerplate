<?php

declare(strict_types = 1);



namespace Api\Infrastructure\Bus\Abstracts;


abstract class ValidatorAbstract implements \Api\Infrastructure\Bus\Interfaces\ValidatorInterface
{
    public function rules(): array
    {
        return [];
    }

    public function messages(): array
    {
        return [];
    }

    public function customAttributes(): array
    {
        return [];
    }
}