<?php

declare(strict_types=1);

$router->group([
                   'prefix' => 'v1',
                   'namespace' => 'Auth\v1',
                   'middleware' => ['auth']
               ], function () use ($router) {
    $router->get(
        'me',
        'MeController@getAll'
    );

    $router->get(
        'test/{id}',
        'MeController@getAll'
    );
});

$router->group([
                   'prefix' => 'v1',
                   'namespace' => 'Example\v1',
                   'middleware' => ['auth']
               ], function () use ($router) {
    $router->get(
        'example',
        'ExampleController@getAll'
    );

    $router->post(
        'example',
        'ExampleController@post'
    );
});
