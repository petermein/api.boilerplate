<?php

namespace Api\Common\OpenApi\Builders;

use Api\Common\OpenApi\Contracts\DescribableObject;
use Api\Common\OpenApi\Contracts\HasDescription;
use Api\Common\OpenApi\Contracts\HasStatusCode;
use Api\Common\OpenApi\Utils\RouteHelper;
use cebe\openapi\spec\Response;
use Illuminate\Container\Container;

class ResponseBuilder
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

    public function getResponseForRoute(array $route): array
    {
        list($request, $response) = $this->routeHelper->reflectOnAction($route['action']);

        $responses = $this->buildCorrectResponse($response);

        //Add aditional faulty responses

        return $responses;
    }

    protected function buildCorrectResponse(string $responseClass): array
    {
        //Instantiate object
        $response = $this->buildMockReponse($responseClass);

        $responseData = [];

        $responseData['description'] = $response instanceof HasDescription
            ? $response->getDescription()
            : (new \ReflectionClass($responseClass))->getShortName();

        $statusCode = $response instanceof HasStatusCode ? $response->getStatusCode() : 200;


        //TODO: add global headers
        //TODO: add object specific headers
        //TODO: add structure for links
        //TODO: add media type
        $responseData['content'] = $this->mediaTypeBuilder->buildMediaType($responseClass);


        return [
            $statusCode => new Response($responseData)
        ];
    }

    protected function buildMockReponse(string $responseClass)
    {
//        $reflection = new ReflectionClass($betterReflectionClass)
        return null;
    }
}
