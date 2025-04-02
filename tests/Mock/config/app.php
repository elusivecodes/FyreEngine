<?php

use Fyre\Cache\Handlers\NullCacher;
use Fyre\DB\Handlers\Sqlite\SqliteConnection;
use Fyre\Log\Handlers\FileLogger;
use Fyre\Mail\Handlers\SendmailMailer;
use Tests\Mock\MockSessionHandler;

return [
    'App' => [
        'baseUri' => 'https://test.com/',
        'value' => 'Test',
    ],
    'Cache' => [
        'default' => [
            'className' => NullCacher::class,
        ],
        'null' => [
            'className' => NullCacher::class,
        ],
    ],
    'Database' => [
        'default' => [
            'className' => SqliteConnection::class,
        ],
        'other' => [
            'className' => SqliteConnection::class,
        ],
    ],
    'Log' => [
        'default' => [
            'className' => FileLogger::class,
        ],
        'other' => [
            'className' => FileLogger::class,
        ],
    ],
    'Mail' => [
        'default' => [
            'className' => SendmailMailer::class,
        ],
        'other' => [
            'className' => SendmailMailer::class,
        ],
    ],
    'Session' => [
        'className' => MockSessionHandler::class,
    ],
];
