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
     * @param \Illuminate\Http\Request $request
     * @param Throwable|Exception $exception
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     * @throws Throwable
     */
    public function render(
        $request,
        Throwable|Exception $e
    ) {
        //Catch nested handler errors in cqrs
        if ($e instanceof HandlerFailedException) {
            $e = $e->getPrevious();
        }

        if ($e instanceof ValidationException) {
            return response()->json([
                                        'error' => true,
                                        'message' => $e->getMessage(),
                                        'errors' => $e->errors(),
                                        'code' => 422
                                    ], 422);
        }

        if ($request->expectsJson()) {
            return response()->json([
                                        'error' => true,
                                        'message' => $e->getMessage(),
                                        'errors' => [],
                                        'code' => $e->statusCode ?? 500
                                    ], $e->statusCode ?? 500);
        }

        return parent::render($request, $e);
    }
}
