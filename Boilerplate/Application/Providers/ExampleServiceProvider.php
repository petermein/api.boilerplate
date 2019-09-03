<?php


namespace Boilerplate\Application\Providers;

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
//        $this->app->bind(
//            'BowlingAlley\Domain\Repositories\Janitor\JanitorWorkingHoursRepository',
//            'BowlingAlley\Infrastructure\Repositories\Janitor\JanitorWorkingHoursEloquentRepository'
//        );
//        $this->app->bind(
//            'BowlingAlley\Domain\Repositories\Janitor\JanitorPaidSickLeaveScheduleRepository',
//            'BowlingAlley\Infrastructure\Repositories\Janitor\JanitorPaidSickLeaveScheduleEloquentRepository'
//        );
    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
//            'BowlingAlley\Domain\Repositories\Janitor\JanitorWorkingHoursRepository',
//            'BowlingAlley\Domain\Repositories\Janitor\JanitorPaidSickLeaveScheduleRepository',
        ];
    }
}