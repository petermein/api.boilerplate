<?php

Route::prefix('v1')->group(function (){

    Route::get(
        'example',
        'Example\ExampleController@getAll'
    );

});
