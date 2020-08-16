<?php


namespace Api\Infrastructure\Bus\Traits;


trait FromArray
{
    public static final function fromArray(array $data = [])
    {
        foreach (get_object_vars($obj = new static) as $property => $default) {
            if (!array_key_exists($property, $data)) {
                continue;
            }

            $obj->{$property} = $data[$property]; // assign value to object
        }
        return $obj;
    }
}