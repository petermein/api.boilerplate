<?php

declare(strict_types = 1);



namespace Api\Infrastructure\Bus;


use Api\Infrastructure\Bus\Buses\QueryBus;
use Api\Infrastructure\Bus\Interfaces\ValidatorLocatorInterface;
use Api\Infrastructure\Bus\Locators\ApplicationHandlerLocator;
use Api\Infrastructure\Bus\Locators\ApplicationSenderLocator;
use Api\Infrastructure\Bus\Locators\ApplicationValidatorLocator;
use Api\Infrastructure\Bus\Middleware\ValidationMiddleware;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Log\Logger;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Messenger\Handler\HandlersLocatorInterface;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\SendMessageMiddleware;
use Symfony\Component\Messenger\Transport\Sender\SendersLocatorInterface;

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
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/bus.php', 'bus');


        $this->app->singleton(QueryBus::class, function () {
            return new QueryBus(new MessageBus([
                $this->makeValidationMiddleware(),
                $this->makeMessageHandleMiddleware(),
                $this->makeSendMessageMiddleware()
            ]));
        });
    }

    /**
     * @return ValidationMiddleware
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function makeValidationMiddleware(): ValidationMiddleware
    {
        $factory = $this->app->make(Factory::class);


        return tap(new ValidationMiddleware($factory, $this->makeValidationLocator()),
            function ($middleware) {
                !$this->loggingIsEnabled() ?: $middleware->setLogger($this->app->make(Logger::class));
            });
    }

    /**
     * @return ValidatorLocatorInterface
     */
    protected function makeValidationLocator(): ValidatorLocatorInterface
    {
        return tap(new ApplicationValidatorLocator($this->app, config('bus.bindings.validators')),
            function ($middleware) {
                //
            });

    }

    /**
     * @return MiddlewareInterface
     */
    protected function makeMessageHandleMiddleware(): MiddlewareInterface
    {
        return tap(new HandleMessageMiddleware($this->makeHandlerLocator()),
            function ($middleware) {
                !$this->loggingIsEnabled() ?: $middleware->setLogger($this->app->make(Logger::class));
            });
    }

    /**
     * @return HandlersLocatorInterface
     */
    protected function makeHandlerLocator(): HandlersLocatorInterface
    {
        return tap(new ApplicationHandlerLocator($this->app, config('bus.bindings.handlers')),
            function ($middleware) {
                //
            });
    }


    /**
     * @return MiddlewareInterface
     */
    protected function makeSendMessageMiddleware(): MiddlewareInterface
    {
        return tap(new SendMessageMiddleware($this->makeSenderLocator()),
            function ($middleware) {
                !$this->loggingIsEnabled() ?: $middleware->setLogger($this->app->make(Logger::class));
            });
    }

    /**
     * @return SendersLocatorInterface
     */
    protected function makeSenderLocator(): SendersLocatorInterface
    {
        return tap(new ApplicationSenderLocator($this->app, config('bus.bindings.senders')), function ($middleware) {
            //
        });
    }

    protected function loggingIsEnabled()
    {
        /** @var bool $loggingEnabled */
        static $loggingEnabled;

        if ($loggingEnabled) {
            return $loggingEnabled;
        }

        return $loggingEnabled = config('bus.logging', false);
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