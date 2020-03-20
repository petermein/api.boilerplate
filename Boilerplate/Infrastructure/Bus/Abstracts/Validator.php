<?php


namespace Boilerplate\Infrastructure\Bus\Abstracts;


abstract class Validator implements \Boilerplate\Infrastructure\Bus\Interfaces\Validator
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