<?php


namespace Api\Infrastructure\Bus\Abstracts;


use Api\Infrastructure\Bus\Traits\FromArray;

abstract class QueryAbstract implements \Api\Infrastructure\Bus\Interfaces\QueryInterface
{
    use FromArray;

    protected $providesHandler = true;
    protected $providesValidators = true;
    protected $providesSenders = true;

    public function getData(): array
    {
        //Create an array from all public variables
        //Remove null values for validation
        return array_filter(get_object_vars($this), fn ($value) => !is_null($value) && $value !== '');
    }

    public function handler(): ?string
    {
        if ($this->providesHandler) {
            //Infer name of handler via pattern
            $reflection = new \ReflectionClass($this);
            $class = $reflection->getShortName();
            $namespace = $reflection->getNamespaceName();
            $handler = $class . 'Handler';

            return $namespace . '\\' . $handler;
        }
    }

    public function validators(): array
    {
        if ($this->providesValidators) {
            $reflection = new \ReflectionClass($this);
            $class = $reflection->getShortName();
            $namespace = $reflection->getNamespaceName();
            $handler = $class . 'Validator';

            return [$namespace . '\\' . $handler];
        }

        return [];
    }

    public function senders(): array
    {
        if ($this->providesSenders) {
            //
        }

        return [];
    }
}