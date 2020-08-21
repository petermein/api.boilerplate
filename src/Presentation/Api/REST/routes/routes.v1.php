<?php

declare(strict_types = 1);


/**
 * @OA\Info(
 *   title="Boilerplate API",
 *   version="1.0.0",
 *   @OA\Contact(
 *     email="peter@infratron.io"
 *   )
 * )
 *
 * @OA\Server(url=API_HOST_V1)
 *
 */
$router->group(['prefix' => 'v1'], function () use ($router) {

    $router->get(
        'example',
        'Example\ExampleController@getAll'
    );

});
