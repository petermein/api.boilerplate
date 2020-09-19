<?php

declare(strict_types=1);

namespace Api\Presentation\Api;

use Api\Application\Auth\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

final class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.


        $this->app['auth']->viaRequest('api', function (Request $request) {
            if ($request->header('auth')) {
                return new User(
                    $request->header('auth'),
                    $request->header('auth-name', ''),
                    explode(' ', $request->header('auth-scope', ''))
                );
            }
        });
    }
}
