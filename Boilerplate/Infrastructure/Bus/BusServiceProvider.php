<?php


namespace Boilerplate\Infrastructure\Bus;


use Boilerplate\Application\Example\Queries\GetAllQuery\GetAllQuery;
use Boilerplate\Application\Example\Queries\GetAllQuery\GetAllQueryHandler;
use Boilerplate\Application\Example\Queries\GetAllQuery\GetAllQueryHandler2;
use Boilerplate\Application\Example\Queries\GetAllQuery\GetAllQueryListener;
use Boilerplate\Application\Example\Queries\GetAllQuery\GetAllQueryValidator;
use Boilerplate\Infrastructure\Bus\Middleware\ValidationMiddleware;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Messenger\Middleware\SendMessageMiddleware;
use Symfony\Component\Messenger\Transport\Sender\SendersLocator;

class BusServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected bool $defer = false;

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
        $this->app->singleton(QueryBus::class, function () {
            return new QueryBus(new MessageBus([
                new ValidationMiddleware($this->app, [
                    GetAllQuery::class => [
                        GetAllQueryValidator::class
                    ],
                ]),
                new HandleMessageMiddleware(
                    new ApplicationHandlerLocator(
                        $this->app,
                        [
                            GetAllQuery::class => [
                                GetAllQueryHandler::class
                            ]
                        ])
                //TODO: write locator with reflection and glob, maybe with caching
                ),
                new SendMessageMiddleware(
                    new SendersLocator(
                        $this->app,
                        [
                            GetAllQuery::class => [
                                GetAllQueryListener::class
                            ],
                        ],
                    )
                )
            ]));
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

        ];
    }
}