<?php

declare(strict_types=1);

use Laravel\Lumen\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    final public function createApplication()
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }
}
