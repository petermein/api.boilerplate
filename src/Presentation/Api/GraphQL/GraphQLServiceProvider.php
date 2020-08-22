<?php

declare(strict_types=1);

namespace Api\Presentation\Api\GraphQL;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

final class GraphQLServiceProvider extends ServiceProvider
{
    public function register()
    {
        //Dirty optimization to only load graphql on correct routes
        $url = $_SERVER['REQUEST_URI'] ?? '';
        $path = \Safe\parse_url($url, PHP_URL_PATH);

        if ($this->app->runningInConsole()
            || Str::startsWith($path, ['/graphql', '/graphiql'])
        ) {
            $this->app->configure('graphql');
            $this->app->register(\Rebing\GraphQL\GraphQLLumenServiceProvider::class);
        }
    }
}
