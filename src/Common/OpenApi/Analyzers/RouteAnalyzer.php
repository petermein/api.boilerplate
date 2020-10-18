<?php


namespace Api\Common\OpenApi\Analyzers;


use Illuminate\Support\Collection;
use Laravel\Lumen\Application;

class RouteAnalyzer
{
    /**
     * @var Application
     */
    private Application $app;

    /**
     * RouteAnalyzer constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function getRouteListForVersion($version): Collection
    {
        $routeCollection = collect(property_exists($this->app, 'router') ? $this->app->router->getRoutes() : $this->app->getRoutes());

        $routeCollection = $routeCollection
            //Version filtering
            ->filter(function ($path, $key) use ($version) {
                return str_contains($key, $version);
            })
            //Group on URI to apply diffrent verbs
            ->groupBy('uri');

        return $routeCollection;
    }

}
