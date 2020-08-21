<?php


namespace Api\Presentation\Api\REST;


use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class RestServiceProvider extends ServiceProvider
{
    public function register()
    {
        //Dirty optimization to only load REST on correct routes
        $url = $_SERVER['REQUEST_URI'] ?? '';
        $path = parse_url($url, PHP_URL_PATH);

        if ($this->app->runningInConsole()
            || Str::startsWith($path, ['/api'])
        ) {
            $this->app->router->group([
                'prefix' => 'api',
                'namespace' => 'Api\Presentation\Api\REST',
                'middleware' => [
                    \BeyondCode\ServerTiming\Middleware\ServerTimingMiddleware::class
                ]
            ], function ($router) {
                include(__DIR__ . '/routes/routes.v1.php');
            });
        }
    }
}