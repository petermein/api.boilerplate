<?php


namespace Api\Presentation\Api\GraphQL\Middleware;


use Closure;
use Illuminate\Contracts\Foundation\Application;

class GraphQLMiddleware
{
    /**
     * @var Application
     */
    private Application $app;

    /**
     * GraphQLMiddleware constructor.
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->app = $application;
    }

    public function handle($request, Closure $next)
    {
        $this->app->configure('graphql');
        $this->app->register(\Rebing\GraphQL\GraphQLLumenServiceProvider::class);
    }

}