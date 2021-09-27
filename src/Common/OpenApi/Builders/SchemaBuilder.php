<?php

namespace Api\Common\OpenApi\Builders;

use cebe\openapi\spec\Schema;

class SchemaBuilder
{
    //TODO: Media type cache

    public function __construct()
    {
    }

    public function buildSchema($class): Schema
    {
        $schemaData = [];
        $schemaData['type'] = 'object';

        $reflection = new \ReflectionClass($class);
        $publicProperties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);

        $properties = [];
        foreach ($publicProperties as $property) {
            $properties[$property->getName()] = $this->buildProperty($property);
        }

        $schemaData['properties'] = $properties;

        return new Schema($schemaData);
    }

    private function buildProperty(\ReflectionProperty $property): Schema
    {
        $schemaData = [];

        $type = $property->getType();

        switch ($type->getName()) {
            case 'string':
                $schemaData = [
                    'type' => 'string'
                ];
                break;
            case 'double':
            case 'float':
                $schemaData = [
                    'type' => 'number'
                ];
                break;
            case 'int':
                $schemaData = [
                    'type' => 'integer'
                ];
                break;
            case 'bool':
                $schemaData = [
                    'type' => 'boolean'
                ];
                break;
            case 'array':
                $schemaData = [
                    'type' => 'array'
                ];
                break;
        }

        $schemaData['nullable'] = $type->allowsNull();


        return new Schema($schemaData);
    }
}
