<?php


namespace Api\Common\OpenApi\Builders;


use Api\Common\OpenApi\Analyzers\ResponseAnalyzer;
use cebe\openapi\spec\Operation;

class OperationBuilder
{
    /**
     * @var ResponseAnalyzer
     */
    private ResponseAnalyzer $responseAnalyzer;

    /**
     * OperationBuilder constructor.
     * @param ResponseAnalyzer $responseAnalyzer
     */
    public function __construct(ResponseAnalyzer $responseAnalyzer)
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
