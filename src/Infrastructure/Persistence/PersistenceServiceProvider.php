<?php

declare(strict_types=1);


namespace Api\Infrastructure\Persistence;

use Illuminate\Support\ServiceProvider;

class PersistenceServiceProvider extends ServiceProvider
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


        //Example
        $this->app->bind(\Api\Application\Example\Repositories\ExampleRepository::class, function ($app) {
            return new \Api\Infrastructure\Persistence\Repositories\Doctrine\ExampleRepository(
                $app['em'],
                $app['em']->getRepository(\Api\Domain\Models\Example::class)
            );
        });
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