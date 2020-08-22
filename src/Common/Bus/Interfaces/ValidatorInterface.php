<?php

declare(strict_types=1);


namespace Api\Common\Bus\Interfaces;

interface ValidatorInterface
{
    public function rules(): array;

    public function messages(): array;

    public function customAttributes(): array;
}
