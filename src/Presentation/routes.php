<?php

declare(strict_types=1);

/**
 * @var $router \Laravel\Lumen\Routing\Router
 */
$router->get('/', function () use ($router) {
    return $router->app->version();
});
