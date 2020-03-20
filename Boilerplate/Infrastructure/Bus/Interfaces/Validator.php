<?php


namespace Boilerplate\Infrastructure\Bus\Interfaces;


interface Validator
{
    public function rules(): array;

    public function messages(): array;

    public function customAttributes(): array;

}