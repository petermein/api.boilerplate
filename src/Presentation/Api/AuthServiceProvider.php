<?php

declare(strict_types=1);

namespace Api\Presentation\Api;

use Api\Application\Auth\Models\UserDto;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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
            if ($request->header('Authorization')) {
                //Validate JWT token in Authorization by only public key

                $userPrefix = config('auth.header_prefix', 'api-user-');

                //Parse user model from headers
                $userHeaders = collect($request->headers->all())->filter(function ($value, $key) use ($userPrefix) {
                    return Str::startsWith($key, [$userPrefix]);
                })
                    ->map(
                        fn($item) => count($item) === 1 ? explode(
                            config('auth.header_array_seperator', ','),
                            $item[0]
                        ) : $item
                    )
                    ->map(fn($item) => count($item) === 1 ? $item[0] : $item)
                    ->mapWithKeys(fn($item, $key) => [\Safe\mb_ereg_replace($userPrefix, "", $key) => $item]);

                return new UserDto(...$userHeaders->toArray());
            }
        });
    }
}
