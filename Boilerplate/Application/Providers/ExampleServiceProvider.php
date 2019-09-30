<?php


namespace Boilerplate\Application\Providers;

use Boilerplate\Application\Example\Queries\GetAllQuery\GetAllQueryHandler;
use Boilerplate\Application\Example\Queries\GetAllQuery\GetAllQueryListener;
use Illuminate\Support\ServiceProvider;

/**
 * @codeCoverageIgnore
 */
class ExampleServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;
    /**
     * Bootstrap the service provider.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(GetAllQueryListener::class);
        $this->app->bind(GetAllQueryHandler::class);
    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [

        ];
    }
}