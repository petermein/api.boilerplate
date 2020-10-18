<?php


namespace Api\Common\OpenApi\Analyzers;


use Api\Common\OpenApi\Utils\RouteHelper;
use cebe\openapi\spec\Response;

class ResponseAnalyzer
{
    /**
     * @var RouteHelper
     */
    private RouteHelper $routeHelper;

    /**
     * @param RouteHelper $routeHelper
     */
    public function __construct(RouteHelper $routeHelper)
    {
        $this->routeHelper = $routeHelper;
    }

    public function getResponseForRoute(array $route): array
    {
        list($request, $response) = $this->routeHelper->reflectOnAction($route['action']);

        return [
            200 => new Response(['description' => $response])
        ];


    }


}
