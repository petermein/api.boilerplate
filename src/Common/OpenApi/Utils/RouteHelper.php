<?php

namespace Api\Common\OpenApi\Utils;

use Api\Common\Bus\Interfaces\RequestInterface;
use Illuminate\Support\Collection;

class RouteHelper
{
    public function reflectOnAction(array $action): array
    {
        $class = $this->getController($action);
        $method = $this->getAction($action);

        //Don't check closures
        if ($method == 'Closure') {
            return [false, false];
        }

        $reflection = new \ReflectionMethod($class, $method);

        //TODO: move to functional magic with colleciton
        $params = $reflection->getParameters();
        $requests = new Collection();

        foreach ($params as $param) {
            $reflectionClass = $param->getDeclaringClass();
            if ($reflectionClass === null) {
                continue;
            }

            $interfaces = $reflectionClass->getInterfaces();

            if (isset($interfaces[RequestInterface::class])) {
                $requests->add($reflectionClass);
            }
        }

        //Validate with atleast one request object
        if ($requests->isEmpty()) {
            return [false, false];
        }

        if ($requests->count() > 1) {
            throw new \Exception('Multiple request params is not yet supported');
        }

        $returnType = $reflection->getReturnType();
        $returnClass = $returnType->getName();

        return [$requests->first()->getName(), $returnClass];
    }

    /**
     * @param array $action
     * @return mixed|string
     */
    public function getController(array $action)
    {
        if (empty($action['uses'])) {
            return 'None';
        }

        return current(explode("@", $action['uses']));
    }

    /**
     * @param array $action
     * @return string
     */
    public function getAction(array $action)
    {
        if (!empty($action['uses'])) {
            $data = $action['uses'];
            if (($pos = strpos($data, "@")) !== false) {
                return \Safe\substr($data, $pos + 1);
            } else {
                return "METHOD NOT FOUND";
            }
        } else {
            return 'Closure';
        }
    }
}
