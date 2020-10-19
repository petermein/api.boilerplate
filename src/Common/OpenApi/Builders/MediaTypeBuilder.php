<?php


namespace Api\Common\OpenApi\Builders;


use Api\Common\OpenApi\Exceptions\NotAllowedException;
use cebe\openapi\spec\MediaType;
use MyCLabs\Enum\Enum;

class MediaTypeBuilder
{
    //TODO: Media type cache

    public function __construct()
    {

    }

    public function buildMediaType($class): array
    {
        $mediaTypeData = [];

        $mockObject = $this->buildMockObjectResponseObject($class);

        $mediaTypeData['example'] = json_encode($mockObject);


//        'schema' => Schema::class,
//            'example' => Type::ANY,
//            'examples' => [Type::STRING, Example::class],
//            'encoding' => [Type::STRING, Encoding::class],


        return [new MediaType($mediaTypeData)];
    }

    private function buildMockObjectResponseObject($class)
    {
        $reflection = new \ReflectionClass($class);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);

        $initData = [];

        foreach ($properties as $property) {
            $type = $property->getType();

            $type->getName();
            $initData[$property->getName()] = $this->generateDataByType($reflection, $property);
        }

        return new $class($initData);
    }

    private function generateDataByType(\ReflectionClass $class, ?\ReflectionProperty $property)
    {
        $type = $property->getType();
        $typeName = $type->getName();

        switch ($typeName) {
            case 'int':
                return 1;
            case 'string':
                return 'string';
            case 'double':
            case 'float':
                return 1.23;
            case 'bool':
                return true;
            case 'resource':
                throw new NotAllowedException('Resource not allowed');
        }

        //Array check docblock
        if ($typeName == 'array') {
            return [];
        }

        //Fil an enum
        $parent = get_parent_class($typeName);
        $enums = $typeName::toArray();
        if ($parent == Enum::class) {
            $enum = array_key_first($enums);
            return $typeName::$enum();
        }

        //Nested class


        //Manual binding


    }

    protected function retrieveArrayReturnTypeFromDocBlock($docBlock)
    {
        $pattern = "#(@[a-zA-Z]+\s*[a-zA-Z0-9, ()_].*)#";
        $matches = [];
        preg_match_all($pattern, $docBlock, $matches, PREG_PATTERN_ORDER);

        dd($matches);

    }

}
