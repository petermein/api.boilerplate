<?php


namespace Boilerplate\Infrastructure\Bus\Abstracts;


use Boilerplate\Infrastructure\Bus\Traits\FromArray;

abstract class Query implements \Boilerplate\Infrastructure\Bus\Interfaces\Query
{
    use FromArray;

    public function getData(): array
    {
        //Create an array from all public variables
        //Remove null values for validation
        return array_filter(get_object_vars($this), fn ($value) => !is_null($value) && $value !== '');
    }

}