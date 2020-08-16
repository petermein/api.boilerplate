<?php
declare(strict_types=1);


namespace Api\Application;


use Api\Application\System\Interfaces\HasRequestMapping;
use Api\Infrastructure\Bus\Abstracts\QueryAbstract;
use BeyondCode\ServerTiming\ServerTiming;
use Illuminate\Support\ServiceProvider;

class ApplicationServiceProvider extends ServiceProvider
{
    /**
     * @var ServerTiming
     */
    private $timing;

    public function __construct($app)
    {
        parent::__construct($app);
        $this->timing = app(ServerTiming::class);
    }

    public function register()
    {
        $this->app->resolving(QueryAbstract::class, function ($object, $app) {

            $this->timing->start('Query building');

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
                    if (!$type->allowsNull() && !isset($data[$propertyName])) {
                        //Validation issue
                        continue;
                    }

                    //7.4: getName undocumented, __toString deprecated
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

            $this->timing->stop('Query building');

            return $object;
        });
    }
}