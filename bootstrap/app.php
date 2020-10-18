<?php

require_once __DIR__ . '/../vendor/autoload.php';

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
    dirname(__DIR__)
))->bootstrap();

date_default_timezone_set(env('APP_TIMEZONE', 'UTC'));

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);

//Cost 2ms
$app->withFacades();

// $app->withEloquent();

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    Api\Application\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    Api\Presentation\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Config Files
|--------------------------------------------------------------------------
|
| Now we will register the "app" configuration file. If the file exists in
| your configuration directory it will be loaded; otherwise, we'll load
| the default version. You may register other files below as needed.
|
*/

$app->configure('app');

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

// $app->middleware([
//     App\Http\Middleware\ExampleMiddleware::class
// ]);

$app->routeMiddleware([
    'auth' => \Api\Presentation\Api\Authenticate::class
]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

/**
 * Laravel
 */
$app->register(Illuminate\Redis\RedisServiceProvider::class);
$app->register(Api\Presentation\Api\AuthServiceProvider::class);

/**
 * Common
 */
$app->register(Api\Common\Bus\BusServiceProvider::class);
$app->register(Api\Common\DTO\DataTransferServiceProvider::class);
$app->register(Api\Common\ExceptionHandling\ExceptionHandlerServiceProvider::class);


/**
 * Infrastructure
 */
$app->register(Api\Infrastructure\Persistence\PersistenceServiceProvider::class);

/**
 * Application
 */
$app->register(Api\Application\ApplicationServiceProvider::class);

/**
 * Presentation
 */
$app->register(Api\Presentation\Api\GraphQL\GraphQLServiceProvider::class);
$app->register(Api\Presentation\Api\REST\RestServiceProvider::class);


/**
 * Root
 */
$app->router->group([
    'middleware' => []
], function ($router) {
    include(__DIR__ . '/../src/Presentation/routes.php');
});

return $app;
