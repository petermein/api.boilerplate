<?php

return [
    /**
     * Open API object info
     */
    'openapi' => [

        /**
         * Open API version number
         */
        'version' => '3.0.2'

    ],

    'versions' => [
        'v1'
    ],

    'info' => [
        'title' => 'Test api',
        'description' => 'Test description',
        'termsOfService' => '',
        'contact' => [],
        'license' => [],
        'version' => []
    ],

    'contacts' => [
        [
            'name' => 'Peter Mein',
            'email' => 'peter@infratron.io',
            'url' => 'https://infratron.io'
        ]
    ],

    'license' => [
        'name' => 'Restricted',
        'url' => 'https://infratron.io/license'
    ],

    'servers' => [
        [
            'url' => 'http://localhost',
            'description' => 'Localhost server',
            'variables' => [
                'id' => [
                    'enum' => ['string'],
                    'default' => 'test',
                    'description' => ''
                ]
            ]
        ]


    ]


];
