<?php

// load our environment files - used to store credentials & configuration
(new Dotenv\Dotenv(__DIR__))->load();

// Load the Illuminate Helper functions
require_once __DIR__ . '/vendor/illuminate/support/helpers.php';

// Load the Helper functions
require_once __DIR__ . '/private/helpers.php';

// Load the Config into the global array
$GLOBALS['config'] = include_once __DIR__ . '/private/config.php';

return [
    'paths' => [
        'migrations' => 'db/migrations',
    ],
    'environments' => [
        'default_database' => 'default',
        'default' => [
            'adapter' => 'mysql',
            'host' => config('database.mysql.hostname', 'localhost'),
            'port' => 3306,
            'name' => config('database.mysql.database'),
            'user' => config('database.mysql.username'),
            'pass' => config('database.mysql.password'),
            'charset' => config('database.mysql.charset', 'utf8mb4'),
            'collation' => config('database.mysql.collation', 'utf8mb4_unicode_ci'),
            'prefix' => config('database.mysql.prefix', ''),
        ],
    ],
];