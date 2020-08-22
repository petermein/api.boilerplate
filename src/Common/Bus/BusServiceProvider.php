<?php

declare(strict_types=1);


namespace Api\Common\Bus;

use Api\Common\Bus\Buses\QueryBus;
use Api\Common\Bus\Interfaces\ValidatorLocatorInterface;
use Api\Common\Bus\Locators\ApplicationHandlerLocator;
use Api\Common\Bus\Locators\ApplicationSenderLocator;
use Api\Common\Bus\Locators\ApplicationValidatorLocator;
use Api\Common\Bus\Middleware\ValidationMiddleware;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Log\Logger;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Messenger\Handler\HandlersLocatorInterface;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\SendMessageMiddleware;
use Symfony\Component\Messenger\Transport\Sender\SendersLocatorInterface;

final class BusServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    private bool $defer = false;

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
    private function makeValidationMiddleware(): ValidationMiddleware
    {
        $factory = $this->app->make(Factory::class);


        return tap(
            new ValidationMiddleware($factory, $this->makeValidationLocator()),
            function ($middleware) {
                !$this->loggingIsEnabled() ?: $middleware->setLogger($this->app->make(Logger::class));
            }
        );
    }

    /**
     * @return ValidatorLocatorInterface
     */
    private function makeValidationLocator(): ValidatorLocatorInterface
    {
        return tap(
            new ApplicationValidatorLocator($this->app, config('bus.bindings.validators')),
            function ($middleware) {
                //
            }
        );
    }

    private function loggingIsEnabled()
    {
        /** @var bool $loggingEnabled */
        static $loggingEnabled;

        if ($loggingEnabled) {
            return $loggingEnabled;
        }

        return $loggingEnabled = config('bus.logging', false);
    }

    /**
     * @return MiddlewareInterface
     */
    private function makeMessageHandleMiddleware(): MiddlewareInterface
    {
        return tap(
            new HandleMessageMiddleware($this->makeHandlerLocator()),
            function ($middleware) {
                !$this->loggingIsEnabled() ?: $middleware->setLogger($this->app->make(Logger::class));
            }
        );
    }

    /**
     * @return HandlersLocatorInterface
     */
    private function makeHandlerLocator(): HandlersLocatorInterface
    {
        return tap(
            new ApplicationHandlerLocator($this->app, config('bus.bindings.handlers')),
            function ($middleware) {
                //
            }
        );
    }

    /**
     * @return MiddlewareInterface
     */
    private function makeSendMessageMiddleware(): MiddlewareInterface
    {
        return tap(
            new SendMessageMiddleware($this->makeSenderLocator()),
            function ($middleware) {
                !$this->loggingIsEnabled() ?: $middleware->setLogger($this->app->make(Logger::class));
            }
        );
    }

    /**
     * @return SendersLocatorInterface
     */
    private function makeSenderLocator(): SendersLocatorInterface
    {
        return tap(new ApplicationSenderLocator($this->app, config('bus.bindings.senders')), function ($middleware) {
            //
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
