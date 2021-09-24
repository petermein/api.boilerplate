<?php

declare(strict_types=1);

namespace Api\Application;

use Api\Application\Auth\Queries\Me\MeQuery;
use Api\Application\Example\Queries\GetAllQuery\GetAllExamplesQuery;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

/**
 * Class ApplicationServiceProvider
 * @package Api\Application
 */
final class ApplicationServiceProvider extends ServiceProvider
{

    public $queries = [
        GetAllExamplesQuery::class,
        MeQuery::class
    ];

    public $commands = [
        GetAllExamplesQuery::class
    ];

    public function register()
    {
        $this->registerQueries();
        $this->registerCommands();
    }

    protected function registerQueries()
    {
        $this->registerRequestAbstract($this->getQueries());
    }

    /**
     * Register request abstracts to the IoC container
     * @param $abstracts
     */
    protected function registerRequestAbstract($abstracts): void
    {
        array_map(function ($tag) {
            $this->app->bind($tag, function ($app) use ($tag) {
                //TOOD: move exection request params to config
                return new $tag($app->make(Request::class)->except('q'));
            });
        }, $abstracts);
    }

    public function getQueries(): array
    {
        //TODO: invert from interface
        //TOOD: cache

        return $this->queries;
    }

    protected function registerCommands()
    {
        $this->registerRequestAbstract($this->getCommands());
    }

    protected function getCommands()
    {
        //TODO: invert from interface
        //TOOD: cache

        return $this->commands;
    }
}
