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
            'templates' => __DIR__ . '/../resources/views',
            'cache'     => getenv('APP_ENVIRONMENT') === 'prod' ? __DIR__ . '/../storage/cache/templates' : false,
        ],
    ],
];