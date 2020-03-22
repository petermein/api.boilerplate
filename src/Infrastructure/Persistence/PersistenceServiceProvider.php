<?php


namespace Api\Infrastructure\Persistence;

class PersistenceServiceProvider
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
        //TODO: Find a way to defer only the used module
        $this->app->bind(
            \Api\Application\Example\Repositories\ExampleRepository::class,
            \Api\Infrastructure\Persistence\Repositories\Doctrine\ExampleRepository::class
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            \Api\Application\Example\Repositories\ExampleRepository::class
        ];
    }
}