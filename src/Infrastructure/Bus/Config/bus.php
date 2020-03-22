<?php

use Api\Application\Example\Queries\GetAllQuery\GetAllExamplesQuery;
use Api\Application\Example\Queries\GetAllQuery\GetAllExamplesQueryValidatorBound;
use Api\Application\System\GlobalValidator;
use Api\Infrastructure\Bus\Interfaces\QueryInterface;

return [
    'logging' => env('BUS_LOGGING_ENABLED', true),

    'bindings' => [

        'validators' => [
            '*' => [
                GlobalValidator::class
            ],
            GetAllExamplesQuery::class => [
                GetAllExamplesQueryValidatorBound::class
            ]
        ],

        'handlers' => [

        ],

        'senders' => [

        ]
    ]
];