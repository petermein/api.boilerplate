<?php

declare(strict_types=1);

$router->group(['prefix' => 'v1',
    'namespace' => 'Example\v1', 'middleware' => []], function () use ($router) {
        $router->get(
            'example',
            'ExampleController@getAll'
        );

        $router->post(
            'example',
            'ExampleController@post'
        );
    });
