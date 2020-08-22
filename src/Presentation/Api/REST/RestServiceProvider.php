<?php

declare(strict_types=1);

namespace Api\Presentation\Api\REST;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

final class RestServiceProvider extends ServiceProvider
{
    public function register()
    {
        //Dirty optimization to only load REST on correct routes
        $url = $_SERVER['REQUEST_URI'] ?? '';
        $path = \Safe\parse_url($url, PHP_URL_PATH);

        if ($this->app->runningInConsole()
            || Str::startsWith($path, ['/api'])
        ) {
            $this->app->router->group([
                'prefix' => 'api',
                'namespace' => 'Api\Presentation\Api\REST',
                'middleware' => [
                ]
            ], function ($router) {
                include(__DIR__ . '/routes/routes.v1.php');
            });
        }
    }
}
