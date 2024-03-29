<?php

declare(strict_types=1);

namespace Api\Common\Bus\Abstracts;

use Api\Common\Bus\Interfaces\RequestInterface;
use Api\Common\DTO\DataTransferObject;

abstract class RequestAbstract extends DataTransferObject implements RequestInterface
{
    /**
     * @var bool
     */
    protected bool $providesHandler = true;
    /**
     * @var bool
     */
    protected bool $providesValidators = true;
    /**
     * @var bool
     */
    protected bool $providesSenders = true;

    final public function getData(): array
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
            $validator = $namespace . '\\' . $handler;

            return [];
        }

        return [];
    }

    public function senders(): array
    {
        return [];
    }
}
