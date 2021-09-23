<?php

namespace Api\Common\DTO;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Spatie\DataTransferObject\DataTransferObjectError;

class DataTransferServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //Exception formatting for DTO object
        $this->app->make(ExceptionHandler::class)->renderer(function (DataTransferObjectError $e, $request) {
            if (Str::contains($e->getMessage(), 'not found on')) {
                return $this->renderUnknownProperties($e);
            }

            if (Str::contains($e->getMessage(), 'Invalid type:')) {
                return $this->renderInvalidType($e);
            }

            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'errors' => [],
                'code' => 422
            ]);
        });
    }

    public function renderUnknownProperties(DataTransferObjectError $e)
    {
        \Safe\preg_match_all('/`(.*?)`/', $e, $errors);
        $errors = collect($errors[1] ?? [])->mapWithKeys(function ($value) {
            return [$value => ['Property not found on request']];
        });

        return response()->json([
            'error' => true,
            'message' => $e->getMessage(),
            'errors' => $errors,
            'code' => 422
        ]);
    }

    public function renderInvalidType(DataTransferObjectError $e)
    {
        //TODO: parse string error

        //example: message: "The following invalid types were encountered: expected `Api\Application\Example\Queries\GetAllQuery\GetAllExamplesQuery::id` to be of type `integer, null`, instead got value `123test`, which is string. expected `Api\Application\Example\Queries\GetAllQuery\GetAllExamplesQuery::number` to be of type `integer, null`, instead got value `12321`, which is string. ",

        return response()->json([
            'error' => true,
            'message' => $e->getMessage(),
            'errors' => [],
            'code' => 422
        ]);
    }
}
