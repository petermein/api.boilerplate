<?php

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
Route::prefix('v1')->group(function (){

    Route::get(
        'example',
        'Example\ExampleController@getAll'
    );

});
