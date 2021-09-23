<?php

declare(strict_types=1);

namespace Api\Common\Bus\Abstracts;

abstract class ValidatorAbstract implements \Api\Common\Bus\Interfaces\ValidatorInterface
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
