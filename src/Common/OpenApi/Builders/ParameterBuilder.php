<?php

namespace Api\Common\OpenApi\Builders;

use Api\Common\OpenApi\Contracts\DescribableObject;
use Api\Common\OpenApi\Contracts\HasDescription;
use Api\Common\OpenApi\Utils\RouteHelper;
use cebe\openapi\spec\Parameter;
use cebe\openapi\spec\Schema;
use Illuminate\Container\Container;
use Illuminate\Routing\Route;

class ParameterBuilder
{
    /**
     * @var RouteHelper
     */
    private RouteHelper $routeHelper;
    /**
     * @var Container
     */
    private Container $application;
    /**
     * @var MediaTypeBuilder
     */
    private MediaTypeBuilder $mediaTypeBuilder;

    /**
     * ResponseAnalyzer constructor.
     * @param Container $application
     * @param RouteHelper $routeHelper
     * @param MediaTypeBuilder $mediaTypeBuilder
     */
    public function __construct(Container $application, RouteHelper $routeHelper, MediaTypeBuilder $mediaTypeBuilder)
    {
        $this->application = $application;
        $this->routeHelper = $routeHelper;
        $this->mediaTypeBuilder = $mediaTypeBuilder;
    }

    public function getParametersForRoute(array $route): array
    {
        //TODO parse parameters from route
        list($request, $response) = $this->routeHelper->reflectOnAction($route['action']);

        $uri = $route['uri'];

        //Parse route

        $routeObject = new Route($route['method'], $route['uri'], $route['action']);

        $parameters = collect($routeObject->parameterNames())->map(function ($param) {
            return $this->buildParameter($param);
        });


        //Add aditional faulty responses

        return $parameters->toArray();
    }

    protected function buildParameter($name): Parameter
    {
        //TODO: \ReflectionUnionType::class
        return new Parameter([
                                 'name' => $name,
                                 'in' => 'uri',
                                 'required' => true,
                                 'schema' => new Schema(['type' => 'string'])
                             ]);

        dd($property->getType());
        //Instantiate object
        $response = $this->buildMockReponse($responseClass);

        $responseData = [];

        $responseData['description'] = $response instanceof HasDescription
            ? $response->getDescription()
            : (new \ReflectionClass($responseClass))->getShortName();


        //TODO: add global headers
        //TODO: add object specific headers
        //TODO: add structure for links
        //TODO: add media type
        $responseData['content'] = $this->mediaTypeBuilder->buildMediaType($responseClass);

        return [
            new Parameter($responseData)
        ];
    }

    protected function buildMockReponse(string $responseClass)
    {
//        $reflection = new ReflectionClass($betterReflectionClass)
        return null;
    }
}
