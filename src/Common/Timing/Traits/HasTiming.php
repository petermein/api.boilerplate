<?php

declare(strict_types = 1);



namespace Api\Common\Timing\Traits;


use BeyondCode\ServerTiming\ServerTiming;

trait HasTiming
{
    public function addMetric(string $metric)
    {
        return $this->getTimer()->addMetric($metric);
    }

    private function getTimer(): ServerTiming
    {
        //TODO: mock for production
        return app(ServerTiming::class);
    }

    public function measure(string $key)
    {
        return $this->getTimer()->measure($key);

    }

    public function hasStartedEvent(string $key): bool
    {
        return $this->getTimer()->hasStartedEvent($key);
    }

    public function start(string $key)
    {
        return $this->getTimer()->start($key);
    }

    public function stop(string $key)
    {
        return $this->getTimer()->stop($key);

    }

    public function setDuration(string $key, $duration)
    {
        return $this->getTimer()->setDuration($key, $duration);

    }

    public function stopAllUnfinishedEvents()
    {
        $this->getTimer()->stopAllUnfinishedEvents();

    }

    public function getDuration(string $key)
    {
        return $this->getTimer()->getDuration($key);
    }
}