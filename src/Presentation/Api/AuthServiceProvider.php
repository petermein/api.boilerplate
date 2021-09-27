<?php

declare(strict_types=1);

namespace Api\Presentation\Api;

use Api\Application\Auth\Models\User;
use Api\Common\OAuth\Exceptions\OAuthServerException;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Key\LocalFileReference;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\StrictValidAt;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;

final class AuthServiceProvider extends ServiceProvider
{
    protected Configuration $jwtConfiguration;

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
                //TODO: enable or disable depending on gate trust

                //Validate JWT token in Authorization by only public key
                $this->initConfiguration();

                $tokenUserParams = $this->validateAuthorization($request);

                $userPrefix = config('auth.header_prefix', 'api-user-');

                //Parse extra user fields from headers
                $userHeadersParams = collect($request->headers->all())->filter(
                    function ($value, $key) use ($userPrefix) {
                        return Str::startsWith($key, [$userPrefix]);
                    }
                )
                    ->map(
                        fn($item) => count($item) === 1 ? explode(
                            config('auth.header_array_seperator', ','),
                            $item[0]
                        ) : $item
                    )
                    ->map(fn($item) => count($item) === 1 ? $item[0] : $item)
                    ->mapWithKeys(fn($item, $key) => [\Safe\mb_ereg_replace($userPrefix, "", $key) => $item])
                    ->toArray();

                $userParams = array_merge($userHeadersParams, $tokenUserParams);

                return new User(...$userParams);
            }
        });
    }

    public function initConfiguration()
    {
        $publicKey = $this->getPublicKey();
        $this->jwtConfiguration = Configuration::forAsymmetricSigner(
            new Sha256(),
            InMemory::plainText(''),
            $publicKey
        );

        $this->jwtConfiguration->setValidationConstraints(
            \class_exists(StrictValidAt::class)
                ? new StrictValidAt(new SystemClock(new DateTimeZone(\date_default_timezone_get())))
                : new LooseValidAt(new SystemClock(new DateTimeZone(\date_default_timezone_get()))),
            new SignedWith(
                new Sha256(),
                $publicKey
            )
        );
    }

    /**
     * @return LocalFileReference
     */
    protected function getPublicKey(): LocalFileReference
    {
        $path = storage_path('keys/oauth-public.key');

        return LocalFileReference::file($path);
    }

    /**
     * @param Request $request
     * @return array
     * @throws OAuthServerException
     */
    public function validateAuthorization(Request $request)
    {
        if ($request->hasHeader('authorization') === false) {
            throw OAuthServerException::accessDenied('Missing "Authorization" header');
        }

        $header = $request->header('authorization');
        $jwt = \trim((string)\preg_replace('/^\s*Bearer\s/', '', $header));

        try {
            // Attempt to parse the JWT
            $token = $this->jwtConfiguration->parser()->parse($jwt);
        } catch (\Lcobucci\JWT\Exception $exception) {
            throw OAuthServerException::accessDenied($exception->getMessage(), null, $exception);
        }

        try {
            // Attempt to validate the JWT
            $constraints = $this->jwtConfiguration->validationConstraints();
            $this->jwtConfiguration->validator()->assert($token, ...$constraints);
        } catch (RequiredConstraintsViolated $exception) {
            throw OAuthServerException::accessDenied('Access token could not be verified');
        }

        $claims = $token->claims();

        return [
            'id' => $claims->get('sub'),
            'access_token_id' => $claims,
            'client_id' => $this->convertSingleRecordAudToString($claims->get('aud')),
            'scopes' => $claims->get('scopes')
        ];
    }

    protected function convertSingleRecordAudToString($aud)
    {
        return \is_array($aud) && \count($aud) === 1 ? $aud[0] : $aud;
    }
}
