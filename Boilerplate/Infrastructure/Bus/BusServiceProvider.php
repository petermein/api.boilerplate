<?php


namespace Boilerplate\Infrastructure\Bus;


use Boilerplate\Application\Example\Queries\GetAllQuery\GetAllQuery;
use Boilerplate\Application\Example\Queries\GetAllQuery\GetAllQueryHandler;
use Boilerplate\Application\Example\Queries\GetAllQuery\GetAllQueryListener;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Messenger\Handler\HandlersLocator;
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
    protected $defer = false;
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
        $this->app->singleton(QueryBus::class, function(){
            return new QueryBus(new MessageBus([
                new HandleMessageMiddleware(
                    new ApplicationHandlerLocator(
                        $this->app,
                        [
                            GetAllQuery::class => [
                                'handler' => GetAllQueryHandler::class
                        ]
                    ]) //todo write locator with reflection and glob, maybe with caching
                ),
                new SendMessageMiddleware(
                    new SendersLocator([
                        GetAllQuery::class => [
                            'outerspace' => GetAllQueryListener::class
                        ],
                    ],
                        $this->app
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