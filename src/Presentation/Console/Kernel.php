<?php

declare(strict_types=1);

namespace Api\Presentation\Console;

use Api\Common\OpenApi\Commands\GenerateOpenApiCommand;
use Api\Presentation\Console\Commands\OpenApiListCommand;
use Api\Presentation\Console\Commands\RouteCommand;
use Api\Presentation\Console\Commands\RoutesCommand;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

final class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        RoutesCommand::class,
        OpenApiListCommand::class,

        //Common
        GenerateOpenApiCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //
    }
}
