<?php


namespace Api\Common\OpenApi\Builders;


use Api\Common\OpenApi\Analyzers\RouteAnalyzer;
use Api\Common\OpenApi\Exceptions\NotAllowedException;
use Api\Common\OpenApi\Utils\RouteHelper;
use Api\Presentation\Api\REST\RESTController;
use cebe\openapi\spec\PathItem;
use cebe\openapi\spec\Paths;
use Illuminate\Support\Collection;
use Laravel\Lumen\Application;

class PathBuilder
{
    /**
     * @var RouteAnalyzer
     */
    private RouteAnalyzer $routeAnalyzer;
    /**
     * @var Application
     */
    private Application $application;
    /**
     * @var OperationBuilder
     */
    private OperationBuilder $operationBuilder;
    /**
     * @var RouteHelper
     */
    private RouteHelper $routeHelper;

    /**
     * PathBuilder constructor.
     * @param Application $application
     * @param RouteAnalyzer $routeAnalyzer
     * @param OperationBuilder $operationBuilder
     * @param RouteHelper $routeHelper
     */
    public function __construct(Application $application, RouteAnalyzer $routeAnalyzer, OperationBuilder $operationBuilder, RouteHelper $routeHelper)
    {
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

        $pathItem = new PathItem([
            'description' => $baseController->description,
            'summary' => $baseController->summary

//TODO:            'servers' => [Server::class],
//TODO:            'parameters' => [Parameter::class],

        ]);

        foreach ($routes as $route) {
            $method = strtolower($route['method']);

            $pathItem->$method = $this->operationBuilder->buildOperation($route);
        }

        return $pathItem;


//        'summary' => Type::STRING,
//            'description' => Type::STRING,
//            'get' => Operation::class,
//            'put' => Operation::class,
//            'post' => Operation::class,
//            'delete' => Operation::class,
//            'options' => Operation::class,
//            'head' => Operation::class,
//            'patch' => Operation::class,
//            'trace' => Operation::class,
//            'servers' => [Server::class],
//            'parameters' => [Parameter::class],

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
     */
    protected function getAction(array $action)
    {
        if (!empty($action['uses'])) {
            $data = $action['uses'];
            if (($pos = strpos($data, "@")) !== false) {
                return substr($data, $pos + 1);
            } else {
                return "METHOD NOT FOUND";
            }
        } else {
            return 'Closure';
        }
    }

    public function instantiateController(string $controllerClass): RESTController
    {
        return $this->application->make($controllerClass);
    }
}
