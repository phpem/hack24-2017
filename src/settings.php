<?php
return [
    'settings' => [
        'displayErrorDetails' => getenv('APP_ENVIRONMENT') !== 'prod', // set to false in production

        // Monolog settings
        'logger'              => [
            'name'  => 'slim-app',
            'path'  => __DIR__ . '/../storage/logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        'view' => [
            'templates' => __DIR__ . '/../resources/views/',
            'cache'     => getenv('APP_ENVIRONMENT') === 'prod' ? __DIR__ . '/../storage/cache/templates' : false,
        ],

        'twitter' => [
            'consumer' => [
                'key' => getenv('TWITTER_CONSUMER_KEY'),
                'secret' => getenv('TWITTER_CONSUMER_SECRET'),
            ],
            'access' => [
                'token' => getenv('TWITTER_TOKEN'),
                'secret' => getenv('TWITTER_TOKEN_SECRET'),
            ],
            'cache' => __DIR__ . '/../storage/cache',
        ],

        'aylien' => [
            'app_id' => getenv("AYLIEN_APP_ID"),
            'key' => getenv("AYLIEN_KEY")
        ]
    ],
];