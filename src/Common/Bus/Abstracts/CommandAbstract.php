<?php

declare(strict_types=1);


namespace Api\Common\Bus\Abstracts;

use Api\Common\Bus\Interfaces\CommandInterface;

abstract class CommandAbstract implements CommandInterface
{
    /**
     * @var bool $providesHandler
     */
    protected $providesHandler = true;
    /**
     * @var bool $providesValidators
     */
    protected $providesValidators = true;
    /**
     * @var bool $providesSenders
     */
    protected $providesSenders = true;

    public function getData(): array
    {
        //Create an array from all public variables
        //Remove null values for validation
        return array_filter(get_object_vars($this), fn($value) => !is_null($value) && $value !== '');
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

        return null;
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
        return [];
    }
}
