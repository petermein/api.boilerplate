<?php


namespace Boilerplate\Infrastructure\Bus\Interfaces;


interface Query
{
    public static function fromArray(array $data = []);

    public function getData(): array;
}