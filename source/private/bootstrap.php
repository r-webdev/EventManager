<?php
/*
Some heplful information about getting path from files

This gets the path for the index.php
    dirname($_SERVER["SCRIPT_FILENAME"])

This gets the path for the file currently being executed
    __DIR__
*/

// Define if error reporting is on
error_reporting(E_ALL);

// Define if errors are displayed.
ini_set('display_errors', 'Off');

// Ensure the servers default timezone is UTC regardless of its true local, this helps ensure all our processing of date time data is based on UTC.
date_default_timezone_set('UTC');

// server should keep session data for AT LEAST 4 hour
ini_set('session.gc_maxlifetime', 7200);

// each client should remember their session id for EXACTLY 2 hour
session_set_cookie_params(7200);

// This is legacy and all development should be stateless, so long as the loadbalancer delivers the same user to the same server each time then this wont be an issue to keep but stateless is the desired development approach.
session_start();


// CORS Preflight OPTIONS
// This is pretty dirty but it seems you want to just explicitly define the methods and allow all origins else you get various issues.
// More discovery is needed around if this is a risk to protecting usage...
// Important to just exit on any OPTIONS request as this is the browser only needing to know information about the endpoint it has requested and does not need content.
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET,POST,PUT,PATCH,DELETE,OPTIONS');
header('Access-Control-Allow-Headers: X-Requested-With, Origin, Content-Type, datatype, Authorization');

// Exit if OPTIONS
if (array_key_exists('REQUEST_METHOD', $_SERVER) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

// Load the composer auto loader file
require_once __DIR__ . '/../vendor/autoload.php';

// Define the dot env class and load the env file.
$dotenv = new Dotenv\Dotenv(__DIR__ . '/../');
$dotenv->load();

// Load the Illuminate Helper functions
require_once __DIR__ . '/../vendor/illuminate/support/helpers.php';

// Load the Helper functions
require_once __DIR__ . '/../private/helpers.php';

// Load the Config into the global array
$GLOBALS['config'] = include_once __DIR__ . '/../private/config.php';

// Use the Database Capsule for managing the DB connection.
use Illuminate\Database\Capsule\Manager as Capsule;

// Define the Capsule class and add the connection based on the stored env settings.
// We are using basic utf8 but we can change this to use multibyte if needed, should be decided earlier in the process and the database needs to be defaulted to multibyte too.
$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => config('database.mysql.hostname', 'localhost'),
    'database' => config('database.mysql.database'),
    'username' => config('database.mysql.username'),
    'password' => config('database.mysql.password'),
    'charset' => config('database.mysql.charset', 'utf8mb4'),
    'collation' => config('database.mysql.collation', 'utf8mb4_unicode_ci'),
    'prefix' => config('database.mysql.prefix', ''),
]);

// Set the event dispatcher used by Eloquent models... (optional)
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
$capsule->setEventDispatcher(new Dispatcher(new Container));

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

// Slim Facade
use SlimFacades\Facade as SlimFacade;
use SlimFacades\Container as SlimContainer;

// Define the slim application and set global settings here, we do not want Slim to display errors.
SlimFacade::setFacadeApplication(new Slim\App([
    'settings' => [
        'displayErrorDetails' => config('debug.enabled', false),
        'determineRouteBeforeAppMiddleware' => true,
    ],
]));

// Add Monolog to the Slim Container
SlimContainer::self()['logger'] = function() {
    $logger = new \Monolog\Logger(strtoupper(config('app.name.short', 'SLIM')));

    $logger->pushHandler(new \Monolog\Handler\StreamHandler(__DIR__ . '/../logs/app.log'));
    $logger->pushHandler(new \Monolog\Handler\BrowserConsoleHandler(\Monolog\Logger::INFO));
    $logger->pushHandler(new \Monolog\Handler\StreamHandler('php://stderr', \Monolog\Logger::ERROR, false));
    if (!empty(config('debug.slack.key', null)) && !empty(config('debug.slack.channel', null)) && !empty(config('debug.slack.botname', null))) {
        $slackHandler = new \Monolog\Handler\SlackHandler(config('debug.slack.key'), '#' . config('debug.slack.channel'), config('debug.slack.botname'));
        $slackHandler->setLevel(\Monolog\Logger::ERROR);
        $logger->pushHandler($slackHandler);
    }

    return $logger;
};

// Add Monolog to the Slim Container Exception Handler
SlimContainer::self()['errorHandler'] = function ($container) {
    return function ($request, $response, $exception) use ($container) {
        $container->logger->error('Exception Handler', [
            'header' => getallheaders(),
            'server' => ['DB_PASSWORD' => '******', 'DEBUG_SLACK_KEY' => '******'] + $_SERVER,
            'exception' => [
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'message' => $exception->getMessage(),
                'trace' => explode("\n", $exception->getTraceAsString()),
            ]
        ]);

        return $container->get('response')->withStatus(500)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode([
                'exception' => [
                    'code' => $exception->getCode(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'trace' => true === config('debug.enabled') ? $exception->getTrace() : null,
                ]
            ]));
    };
};

//Override the default Not Found Handler to return the BardSpear Web Application
SlimContainer::self()['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        $blade = new \Philo\Blade\Blade(config('blade.paths.views'), config('blade.paths.cache'));

        return $response->write(
            $blade->view()
                ->make('default.index')
                ->with([])
                ->render()
        );
    };
};