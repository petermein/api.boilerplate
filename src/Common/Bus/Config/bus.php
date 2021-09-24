<?php

declare(strict_types=1);


use Api\Application\Example\Queries\GetAllQuery\GetAllExamplesQuery;
use Api\Application\Example\Queries\GetAllQuery\GetAllExamplesQueryValidatorBound;

return [
    'logging' => env('BUS_LOGGING_ENABLED', true),

    'bindings' => [

        'validators' => [
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
