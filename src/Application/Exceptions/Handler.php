<?php

declare(strict_types=1);


namespace Api\Application\Exceptions;

use Api\Domain\Exceptions\Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Throwable;

final class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Throwable $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param Throwable|Exception $exception
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     * @throws Throwable
     */
    public function render($request, Throwable|Exception $exception)
    {
        //Catch nested handler errors in cqrs
        if ($exception instanceof HandlerFailedException) {
            $exception = $exception->getPrevious();
        }

        if ($exception instanceof ValidationException) {
            return response()->json([
                'error' => true,
                'message' => $exception->getMessage(),
                'errors' => $exception->errors(),
                'code' => 422
            ], 422);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'error' => true,
                'message' => $exception->getMessage(),
                'errors' => [],
                'code' => $exception->statusCode ?? 500
            ], $exception->statusCode ?? 500);
        }

        return parent::render($request, $exception);
    }
}
