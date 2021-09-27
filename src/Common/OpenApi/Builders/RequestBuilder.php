<?php

namespace Api\Common\OpenApi\Builders;

use Api\Common\OpenApi\Contracts\DescribableObject;
use Api\Common\OpenApi\Contracts\HasDescription;
use Api\Common\OpenApi\Contracts\HasStatusCode;
use Api\Common\OpenApi\Utils\RouteHelper;
use cebe\openapi\spec\Request;
use cebe\openapi\spec\RequestBody;
use Illuminate\Container\Container;

class RequestBuilder
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
     * RequestAnalyzer constructor.
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

    public function getRequestForRoute(array $route): RequestBody
    {
        list($request, $response) = $this->routeHelper->reflectOnAction($route['action']);

        $request = $this->buildCorrectRequest($request);

        //Add aditional faulty responses

        return $request;
    }

    protected function buildCorrectRequest(string $requestClass): RequestBody
    {
        //Instantiate object
        $request = $this->buildMockReponse($requestClass);

        $requestData = [];

        $requestData['description'] = $request instanceof HasDescription
            ? $request->getDescription()
            : (new \ReflectionClass($requestClass))->getShortName();

        $statusCode = $request instanceof HasStatusCode ? $request->getStatusCode() : 200;

        //TODO: add global headers
        //TODO: add object specific headers
        //TODO: add structure for links
        //TODO: add media type

        $requestData['content'] = $this->mediaTypeBuilder->buildMediaType($requestClass);


        return new RequestBody($requestData);
    }

    protected function buildMockReponse(string $requestClass)
    {
//        $reflection = new ReflectionClass($betterReflectionClass)
        return null;
    }
}
