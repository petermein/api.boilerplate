<?php

declare(strict_types = 1);


$router->get('/', function () use ($router) {
    return $router->app->version();
});
