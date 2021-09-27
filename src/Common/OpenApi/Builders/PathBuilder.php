<?php

namespace Api\Common\OpenApi\Builders;

use Api\Common\OpenApi\Analyzers\RouteAnalyzer;
use Api\Common\OpenApi\Contracts\HasDescription;
use Api\Common\OpenApi\Contracts\HasSummary;
use Api\Common\OpenApi\Exceptions\NotAllowedException;
use Api\Common\OpenApi\Utils\RouteHelper;
use cebe\openapi\spec\PathItem;
use cebe\openapi\spec\Paths;
use Illuminate\Container\Container;
use Illuminate\Support\Collection;

class PathBuilder
{
    /**
     * @var RouteAnalyzer
     */
    protected RouteAnalyzer $routeAnalyzer;
    /**
     * @var Container
     */
    protected Container $application;
    /**
     * @var OperationBuilder
     */
    protected OperationBuilder $operationBuilder;
    /**
     * @var RouteHelper
     */
    protected RouteHelper $routeHelper;

    /**
     * PathBuilder constructor.
     * @param Container $application
     * @param RouteAnalyzer $routeAnalyzer
     * @param OperationBuilder $operationBuilder
     * @param RouteHelper $routeHelper
     */
    public function __construct(
        Container $application,
        RouteAnalyzer $routeAnalyzer,
        OperationBuilder $operationBuilder,
        RouteHelper $routeHelper
    ) {
        $this->application = $application;
        $this->routeAnalyzer = $routeAnalyzer;
        $this->operationBuilder = $operationBuilder;
        $this->routeHelper = $routeHelper;
    }


    public function generatePaths($version): Paths
    {
        $routes = $this->routeAnalyzer->getRouteListForVersion($version);

        $paths = $routes->transform(function ($item, $uri) {
            return $this->buildPath($uri, $item);
        });

        return new Paths($paths->toArray());
    }

    public function buildPath($uri, Collection $routes): PathItem
    {
        //We presume all routes are in the same controller
        $baseRoute = $routes->first();

        $controllerClass = $this->routeHelper->getController($baseRoute['action']);

        if ($controllerClass == false) {
            throw new NotAllowedException('No class found for path');
        }

        $baseController = $this->instantiateController($controllerClass);

        $pathData = [];

        if ($baseController instanceof HasDescription) {
            $pathData['description'] = $baseController->getDescription();
        }

        if ($baseController instanceof HasSummary) {
            $pathData['summary'] = $baseController->getSummary();
        }

        $pathItem = new PathItem(
            $pathData

//TODO:            'servers' => [Server::class],
//TODO:            'parameters' => [Parameter::class],
        );

        $tag = $this->extractTagFromClass($controllerClass);
        foreach ($routes as $route) {
            $method = strtolower($route['method']);

            $operation = $this->operationBuilder->buildOperation($route);
            $operation->tags = [$tag];

            $pathItem->$method = $operation;
        }

        return $pathItem;
    }

    public function instantiateController(string $controllerClass)
    {
        return $this->application->make($controllerClass);
    }

    protected function extractTagFromClass($class)
    {
        //TODO move to dynamic
        return explode("\\", str_replace("Api\\Presentation\\Api\\REST\\", "", $class))[0] ?? null;
    }

    /**
     * @param array $action
     * @return bool|string
     */
    protected function getController(array $action)
    {
        if (empty($action['uses'])) {
            return false;
        }

        return current(explode("@", $action['uses']));
    }

    /**
     * @param array $action
     * @return string
     *
     * @throws \Safe\Exceptions\StringsException
     */
    protected function getAction(array $action)
    {
        if (!empty($action['uses'])) {
            $data = $action['uses'];
            if (($pos = strpos($data, "@")) !== false) {
                return \Safe\substr($data, $pos + 1);
            }

            return "METHOD NOT FOUND";
        }

        return 'Closure';
    }
}
