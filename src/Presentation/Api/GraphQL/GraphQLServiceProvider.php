<?php


namespace Api\Presentation\Api\GraphQL;


use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class GraphQLServiceProvider extends ServiceProvider
{
    public function register()
    {
        //Dirty optimization to only load graphql on correct routes
        $url = $_SERVER['REQUEST_URI'] ?? '';
        $path = parse_url($url, PHP_URL_PATH);

        if ($this->app->runningInConsole()
            || Str::startsWith($path, ['/graphql', '/graphiql'])
        ) {
            $this->app->configure('graphql');
            $this->app->register(\Rebing\GraphQL\GraphQLLumenServiceProvider::class);
        }
    }
}