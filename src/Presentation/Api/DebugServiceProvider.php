<?php

namespace Api\Presentation\Api;

use Illuminate\Support\ServiceProvider;

class DebugServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(\Clockwork\Support\Lumen\ClockworkServiceProvider::class);
    }

}
