<?php

declare(strict_types=1);

namespace Api\Common\DTO;


use Spatie\DataTransferObject\DataTransferObjectError;

abstract class DataTransferObject extends \Spatie\DataTransferObject\DataTransferObject
{
    public function setStrictProperties(bool $strict)
    {
        $this->ignoreMissing = !$strict;
    }

    public function fill(array $parameters)
    {
        $validators = $this->getFieldValidators();

        $valueCaster = $this->getValueCaster();

        /** string[] */
        $invalidTypes = [];

        foreach ($validators as $field => $validator) {
            if (
                !isset($parameters[$field])
                && !$validator->hasDefaultValue
                && !$validator->isNullable
            ) {
                throw DataTransferObjectError::uninitialized(
                    static::class,
                    $field
                );
            }

            $value = $parameters[$field] ?? $this->{$field} ?? null;

            $value = $this->castValue($valueCaster, $validator, $value);

            if (!$validator->isValidType($value)) {
                $invalidTypes[] = DataTransferObjectError::invalidTypeMessage(
                    static::class,
                    $field,
                    $validator->allowedTypes,
                    $value
                );

                continue;
            }

            $this->{$field} = $value;

            unset($parameters[$field]);
        }

        if ($invalidTypes) {
            DataTransferObjectError::invalidTypes($invalidTypes);
        }

        if (!$this->ignoreMissing && count($parameters)) {
            throw DataTransferObjectError::unknownProperties(array_keys($parameters), static::class);
        }
    }
}
