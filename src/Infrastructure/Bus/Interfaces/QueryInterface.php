<?php


namespace Api\Infrastructure\Bus\Interfaces;


interface QueryInterface
{
    public static function fromArray(array $data = []);

    public function getData(): array;

    public function handler(): ?string;

    public function validators(): array;

    public function senders(): array;
}