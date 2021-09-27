<?php

namespace Api\Common\OAuth\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class OAuthServerException extends Exception
{
    /**
     * @var int
     */
    private $httpStatusCode;

    /**
     * @var string
     */
    private $errorType;

    /**
     * @var null|string
     */
    private $hint;

    /**
     * @var null|string
     */
    private $redirectUri;

    /**
     * @var array
     */
    private $payload;

    /**
     * @var Request
     */
    private $serverRequest;

    /**
     * Throw a new exception.
     *
     * @param string $message Error message
     * @param int $code Error code
     * @param string $errorType Error type
     * @param int $httpStatusCode HTTP status code to send (default = 400)
     * @param null|string $hint A helper hint
     * @param null|string $redirectUri A HTTP URI to redirect the user back to
     * @param Throwable $previous Previous exception
     */
    public function __construct(
        $message,
        $code,
        $errorType,
        $httpStatusCode = 400,
        $hint = null,
        $redirectUri = null,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->httpStatusCode = $httpStatusCode;
        $this->errorType = $errorType;
        $this->hint = $hint;
        $this->redirectUri = $redirectUri;
        $this->payload = [
            'error' => $errorType,
            'error_description' => $message,
        ];
        if ($hint !== null) {
            $this->payload['hint'] = $hint;
        }
    }

    /**
     * Unsupported grant type error.
     *
     * @return static
     */
    public static function unsupportedGrantType()
    {
        $errorMessage = 'The authorization grant type is not supported by the authorization server.';
        $hint = 'Check that all required parameters have been provided';

        return new static($errorMessage, 2, 'unsupported_grant_type', 400, $hint);
    }

    /**
     * Invalid request error.
     *
     * @param string $parameter The invalid parameter
     * @param null|string $hint
     * @param Throwable $previous Previous exception
     *
     * @return static
     */
    public static function invalidRequest($parameter, $hint = null, Throwable $previous = null)
    {
        $errorMessage = 'The request is missing a required parameter, includes an invalid parameter value, ' .
            'includes a parameter more than once, or is otherwise malformed.';
        $hint = ($hint === null) ? \sprintf('Check the `%s` parameter', $parameter) : $hint;

        return new static($errorMessage, 3, 'invalid_request', 400, $hint, null, $previous);
    }

    /**
     * Invalid client error.
     *
     * @param ServerRequestInterface $serverRequest
     *
     * @return static
     */
    public static function invalidClient(Request $serverRequest)
    {
        $exception = new static('Client authentication failed', 4, 'invalid_client', 401);

        $exception->setServerRequest($serverRequest);

        return $exception;
    }

    /**
     * @param Request $serverRequest
     */
    public function setServerRequest(Request $serverRequest)
    {
        $this->serverRequest = $serverRequest;
    }

    /**
     * Invalid scope error.
     *
     * @param string $scope The bad scope
     * @param null|string $redirectUri A HTTP URI to redirect the user back to
     *
     * @return static
     */
    public static function invalidScope($scope, $redirectUri = null)
    {
        $errorMessage = 'The requested scope is invalid, unknown, or malformed';

        if (empty($scope)) {
            $hint = 'Specify a scope in the request or set a default scope';
        } else {
            $hint = \sprintf(
                'Check the `%s` scope',
                \htmlspecialchars($scope, ENT_QUOTES, 'UTF-8', false)
            );
        }

        return new static($errorMessage, 5, 'invalid_scope', 400, $hint, $redirectUri);
    }

    /**
     * Invalid credentials error.
     *
     * @return static
     */
    public static function invalidCredentials()
    {
        return new static('The user credentials were incorrect.', 6, 'invalid_grant', 400);
    }

    /**
     * Server error.
     *
     * @param string $hint
     * @param Throwable $previous
     *
     * @return static
     *
     * @codeCoverageIgnore
     */
    public static function serverError($hint, Throwable $previous = null)
    {
        return new static(
            'The authorization server encountered an unexpected condition which prevented it from fulfilling'
            . ' the request: ' . $hint,
            7,
            'server_error',
            500,
            null,
            null,
            $previous
        );
    }

    /**
     * Invalid refresh token.
     *
     * @param null|string $hint
     * @param Throwable $previous
     *
     * @return static
     */
    public static function invalidRefreshToken($hint = null, Throwable $previous = null)
    {
        return new static('The refresh token is invalid.', 8, 'invalid_request', 401, $hint, null, $previous);
    }

    /**
     * Access denied.
     *
     * @param null|string $hint
     * @param null|string $redirectUri
     * @param Throwable $previous
     *
     * @return static
     */
    public static function accessDenied($hint = null, $redirectUri = null, Throwable $previous = null)
    {
        return new static(
            'The resource owner or authorization server denied the request.',
            9,
            'access_denied',
            401,
            $hint,
            $redirectUri,
            $previous
        );
    }

    /**
     * Invalid grant.
     *
     * @param string $hint
     *
     * @return static
     */
    public static function invalidGrant($hint = '')
    {
        return new static(
            'The provided authorization grant (e.g., authorization code, resource owner credentials) or refresh token '
            . 'is invalid, expired, revoked, does not match the redirection URI used in the authorization request, '
            . 'or was issued to another client.',
            10,
            'invalid_grant',
            400,
            $hint
        );
    }

    /**
     * @return string
     */
    public function getErrorType()
    {
        return $this->errorType;
    }

    /**
     * Generate a HTTP response.
     *
     * @param ResponseInterface $response
     * @param bool $useFragment True if errors should be in the URI fragment instead of query string
     * @param int $jsonOptions options passed to json_encode
     *
     * @return ResponseInterface
     */
    public function generateHttpResponse(ResponseInterface $response, $useFragment = false, $jsonOptions = 0)
    {
        $headers = $this->getHttpHeaders();

        $payload = $this->getPayload();

        if ($this->redirectUri !== null) {
            if ($useFragment === true) {
                $this->redirectUri .= (\strstr($this->redirectUri, '#') === false) ? '#' : '&';
            } else {
                $this->redirectUri .= (\strstr($this->redirectUri, '?') === false) ? '?' : '&';
            }

            return $response->withStatus(302)->withHeader('Location', $this->redirectUri . \http_build_query($payload));
        }

        foreach ($headers as $header => $content) {
            $response = $response->withHeader($header, $content);
        }

        $responseBody = \json_encode($payload, $jsonOptions) ?: 'JSON encoding of payload failed';

        $response->getBody()->write($responseBody);

        return $response->withStatus($this->getHttpStatusCode());
    }

    /**
     * Get all headers that have to be send with the error response.
     *
     * @return array Array with header values
     */
    public function getHttpHeaders()
    {
        $headers = [
            'Content-type' => 'application/json',
        ];

        // Add "WWW-Authenticate" header
        //
        // RFC 6749, section 5.2.:
        // "If the client attempted to authenticate via the 'Authorization'
        // request header field, the authorization server MUST
        // respond with an HTTP 401 (Unauthorized) status code and
        // include the "WWW-Authenticate" response header field
        // matching the authentication scheme used by the client.
        if ($this->errorType === 'invalid_client' && $this->requestHasAuthorizationHeader()) {
            $authScheme = \strpos(
                $this->serverRequest->header('Authorization')[0],
                'Bearer'
            ) === 0 ? 'Bearer' : 'Basic';

            $headers['WWW-Authenticate'] = $authScheme . ' realm="OAuth"';
        }

        return $headers;
    }

    /**
     * Check if the request has a non-empty 'Authorization' header value.
     *
     * Returns true if the header is present and not an empty string, false
     * otherwise.
     *
     * @return bool
     */
    private function requestHasAuthorizationHeader()
    {
        if (!$this->serverRequest->hasHeader('Authorization')) {
            return false;
        }

        $authorizationHeader = $this->serverRequest->header('Authorization');

        // Common .htaccess configurations yield an empty string for the
        // 'Authorization' header when one is not provided by the client.
        // For practical purposes that case should be treated as though the
        // header isn't present.
        // See https://github.com/thephpleague/oauth2-server/issues/1162
        if (empty($authorizationHeader) || empty($authorizationHeader[0])) {
            return false;
        }

        return true;
    }

    /**
     * Returns the current payload.
     *
     * @return array
     */
    public function getPayload()
    {
        $payload = $this->payload;

        // The "message" property is deprecated and replaced by "error_description"
        // TODO: remove "message" property
        if (isset($payload['error_description']) && !isset($payload['message'])) {
            $payload['message'] = $payload['error_description'];
        }

        return $payload;
    }

    /**
     * Updates the current payload.
     *
     * @param array $payload
     */
    public function setPayload(array $payload)
    {
        $this->payload = $payload;
    }

    /**
     * Returns the HTTP status code to send when the exceptions is output.
     *
     * @return int
     */
    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }

    /**
     * Check if the exception has an associated redirect URI.
     *
     * Returns whether the exception includes a redirect, since
     * getHttpStatusCode() doesn't return a 302 when there's a
     * redirect enabled. This helps when you want to override local
     * error pages but want to let redirects through.
     *
     * @return bool
     */
    public function hasRedirect()
    {
        return $this->redirectUri !== null;
    }

    /**
     * Returns the Redirect URI used for redirecting.
     *
     * @return string|null
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    /**
     * @return null|string
     */
    public function getHint()
    {
        return $this->hint;
    }
}
