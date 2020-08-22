<?php

declare(strict_types=1);

namespace Api\Application;

use Api\Application\System\Interfaces\HasRequestMapping;
use Api\Common\Bus\Abstracts\QueryAbstract;
use Illuminate\Support\ServiceProvider;

/**
 * Class ApplicationServiceProvider
 * @package Api\Application
 */
final class ApplicationServiceProvider extends ServiceProvider
{
    public function register()
    {
        //TODO: rework with dto
        $this->app->resolving(QueryAbstract::class, function ($object, $app) {


            $data = $app->request->all();

            $classReflection = new \ReflectionClass($object);
            $properties = $classReflection->getProperties();

            foreach ($properties as $property) {
                //Don't try to fill properties that are not public
                if ($property->getModifiers() & ~\ReflectionProperty::IS_PUBLIC) {
                    continue;
                }

                $propertyName = $property->getName();
                $propertyType = $type = $property->getType();

                //TODO build strict property type casting, with validation errors
                if ($propertyType !== null) {
                    if (!$type->allowsNull() && !array_key_exists($propertyName, $data)) {
                        //Validation issue
                        continue;
                    }

                    //7.4: getName undocumented, __toString deprecated
                    /** @phpstan-ignore-next-line */
                    if ($type->getName() === 'int') {
                        $object->{$propertyName} = (int)$data[$propertyName];
                    }
                } else {
                    $object->{$propertyName} = $data[$propertyName] ?? null;
                }
            }

            if ($object instanceof HasRequestMapping) {
                call_user_func([$object, 'requestMapping'], $app->request);
            }


            return $object;
        });
    }
}
