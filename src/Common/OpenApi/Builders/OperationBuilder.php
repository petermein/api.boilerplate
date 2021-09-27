<?php

namespace Api\Common\OpenApi\Builders;

use cebe\openapi\spec\Operation;

class OperationBuilder
{
    protected ParameterBuilder $parameterBuilder;
    protected RequestBuilder $requestBuilder;
    /**
     * @var ResponseBuilder
     */
    private ResponseBuilder $responseBuilder;

    /**
     * OperationBuilder constructor.
     * @param ResponseBuilder $responseBuilder
     * @param ParameterBuilder $parameterBuilder
     */
    public function __construct(
        ResponseBuilder $responseBuilder,
        ParameterBuilder $parameterBuilder,
        RequestBuilder $requestBuilder
    ) {
        $this->responseAnalyzer = $responseBuilder;
        $this->parameterBuilder = $parameterBuilder;
        $this->requestBuilder = $requestBuilder;
    }

    public function buildOperation(array $route): Operation
    {
        $responses = $this->responseAnalyzer->getResponseForRoute($route);
        $parameters = $this->parameterBuilder->getParametersForRoute($route);
        $requestBody = $this->requestBuilder->getRequestForRoute($route);

        return new Operation(
            [
                'responses' => $responses,
                'parameters' => $parameters,
                'requestBody' => $requestBody
            ]
        );
    }
}
