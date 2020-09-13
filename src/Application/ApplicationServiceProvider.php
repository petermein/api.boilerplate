<?php

declare(strict_types=1);

namespace Api\Application;

use Api\Common\Bus\Abstracts\RequestAbstract;
use Illuminate\Support\ServiceProvider;

/**
 * Class ApplicationServiceProvider
 * @package Api\Application
 */
final class ApplicationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->resolving(RequestAbstract::class, function (RequestAbstract &$object, $app) {
            $object->setStrictProperties(config('api.strict_dto_properties', true));
            $object->fill($app->request->except('q'));
        });
    }
}
