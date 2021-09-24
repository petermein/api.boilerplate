<?php

namespace Api\Common\OpenApi\Builders;

use Api\Common\DTO\DataTransferObject;
use Api\Common\OpenApi\Exceptions\NotAllowedException;
use cebe\openapi\spec\MediaType;
use MyCLabs\Enum\Enum;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use phpDocumentor\Reflection\FqsenResolver;
use phpDocumentor\Reflection\TypeResolver;
use phpDocumentor\Reflection\Types\Array_;
use phpDocumentor\Reflection\Types\Compound;
use phpDocumentor\Reflection\Types\ContextFactory;
use phpDocumentor\Reflection\Types\Object_;
use phpDocumentor\Reflection\Types\Scalar;

class MediaTypeBuilder
{
    //TODO: Media type cache
    /**
     * @var SchemaBuilder
     */
    private SchemaBuilder $schemaBuilder;

    /**
     * MediaTypeBuilder constructor.
     * @param SchemaBuilder $schemaBuilder
     */
    public function __construct(SchemaBuilder $schemaBuilder)
    {
        $this->schemaBuilder = $schemaBuilder;
    }

    public function buildMediaType($class): array
    {
        $mediaTypeData = [];

        $mockObject = $this->buildMockObjectResponseObject($class);


        $mediaTypeData['example'] = $mockObject;
        $mediaTypeData['schema'] = $this->schemaBuilder->buildSchema($class);

//        'schema' => Schema::class,
//            'example' => Type::ANY,
//            'examples' => [Type::STRING, Example::class],
//            'encoding' => [Type::STRING, Encoding::class],


        return ['application/json' => new MediaType($mediaTypeData)];
    }

    private function buildMockObjectResponseObject($class)
    {
        $reflection = new \ReflectionClass($class);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);

        $initData = [];

        foreach ($properties as $property) {
            $type = $property->getType();

            $type->getName();
            $value = $this->generateDataByType($reflection, $property);
            $initData[$property->getName()] = $value;
        }

        /** @var DataTransferObject $object */
        $object = new $class($initData);
        return $object;
    }

    private function generateDataByType(?\ReflectionClass $class, ?\ReflectionProperty $property, string $typeName = null)
    {
        $type = $property->getType();
        $typeName = $typeName ?? $type->getName();

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
            $factory = \phpDocumentor\Reflection\DocBlockFactory::createInstance();

            $docBlock = $factory->create($property->getDocComment());
            $vars = $docBlock->getTagsByName('var');
            /** @var Var_ $var */
            $var = $vars[0]; //Select first var notation


            /** @var Compound $type */
            $type = $var->getType();

            if ($type instanceof Compound) {
                foreach ($type->getIterator() as $item) {
                    if ($item instanceof Array_) {
                        /** @var Object_|Scalar $object */
                        $object = $item->getValueType();
                        break;
                    }
                }
            } elseif ($type instanceof Array_) {
                /** @var Object_|Scalar $object */
                $object = $type->getValueType();
            }

            if ($object instanceof Object_) {
                $typeResolver = new TypeResolver();
                $contextFactory = new ContextFactory();

                //Resolve potential imports
                $context = $contextFactory->createFromReflector($class);
                $name = $object->getFqsen()->getName();
                $valueType = $typeResolver->resolve($name, $context);

                return [
                    $this->buildMockObjectResponseObject((string)$valueType->getFqsen())
                ];
            }

            return [
                (string)$object
            ];
        }

        //Fil an enum
        $parent = get_parent_class($typeName);
        if ($parent == Enum::class) {
            $enums = $typeName::toArray();
            $enum = array_key_first($enums);
            return $typeName::$enum();
        }

        //Nested class
        $factory = \phpDocumentor\Reflection\DocBlockFactory::createInstance();

        $docBlock = $factory->create($property->getDocComment());
        $vars = $docBlock->getTagsByName('var');
        /** @var Var_ $var */
        $var = $vars[0]; //Select first var notation
        /** @var Compound $valueType */
        $valueType = $var->getType();

        $typeResolver = new TypeResolver();
        $fqsenResolver = new FqsenResolver();

        $contextFactory = new ContextFactory();

        //Resolve potential imports
        $context = $contextFactory->createFromReflector($class);
        $name = $valueType->getFqsen()->getName();
        $valueType = $typeResolver->resolve($name, $context);

        return $this->buildMockObjectResponseObject((string)$valueType->getFqsen());

        //Manual binding
    }

    protected function retrieveArrayReturnTypeFromDocBlock($docBlock)
    {
        $pattern = "#(@[a-zA-Z]+\s*[a-zA-Z0-9, ()_].*)#";
        $matches = [];
        \Safe\preg_match_all($pattern, $docBlock, $matches, PREG_PATTERN_ORDER);

        dd($matches);
    }
}
