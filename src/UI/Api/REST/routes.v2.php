<?php


/**
 * @OA\Server(url=API_HOST_V2)
 */
Route::prefix('v1')->group(function (){

    Route::get(
        'example',
        'Example\ExampleController@getAll'
    );

});
