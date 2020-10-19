<?php


namespace Api\Common\OpenApi\Builders;


use cebe\openapi\spec\Operation;

class OperationBuilder
{
    /**
     * @var ResponseBuilder
     */
    private ResponseBuilder $responseAnalyzer;

    /**
     * OperationBuilder constructor.
     * @param ResponseBuilder $responseAnalyzer
     */
    public function __construct(ResponseBuilder $responseAnalyzer)
    {
        $this->responseAnalyzer = $responseAnalyzer;
    }

    public function buildOperation(array $route): Operation
    {
        $responses = $this->responseAnalyzer->getResponseForRoute($route);

        return new Operation(
            ['responses' => $responses]
        );
    }

}
