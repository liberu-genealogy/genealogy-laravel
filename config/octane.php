<?php

use Laravel\Octane\Listeners\EnsureUploadedFilesAreValid;
use Laravel\Octane\Listeners\EnsureUploadedFilesCanBeMoved;
use Laravel\Octane\Listeners\FlushTemporaryContainerInstances;
use Laravel\Octane\Listeners\ReportException;
use Laravel\Octane\Listeners\StopWorkerIfNecessary;
use Laravel\Octane\Events\RequestReceived;
use Laravel\Octane\Events\RequestHandled;
use Laravel\Octane\Events\RequestTerminated;
use Laravel\Octane\Events\TaskReceived;
use Laravel\Octane\Events\TaskTerminated;
use Laravel\Octane\Events\TickReceived;
use Laravel\Octane\Events\TickTerminated;
use Laravel\Octane\Events\WorkerErrorOccurred;
use Laravel\Octane\Events\WorkerStarting;
use Laravel\Octane\Events\WorkerStopping;
use Laravel\Octane\Facades\Octane;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Server
    |--------------------------------------------------------------------------
    |
    | This value determines the default server that will be used by the
    | "serve" command when no server is specified. You may set this to
    | any of the servers supported by Octane using the server name.
    |
    */

    'server' => env('OCTANE_SERVER', 'swoole'),

    /*
    |--------------------------------------------------------------------------
    | Octane Servers
    |--------------------------------------------------------------------------
    |
    | All of the servers supported by Octane and their respective options.
    | You may customize the options for each server as needed for your
    | application. You should ensure the "state" for each server is an
    | array of the application state that should be managed by Octane.
    |
    */

    'servers' => [

        'swoole' => [
            'host' => env('OCTANE_HOST', '0.0.0.0'),
            'port' => env('OCTANE_PORT', 8000),
            'workers' => env('OCTANE_WORKERS', 'auto'),
            'task_workers' => env('OCTANE_TASK_WORKERS', 'auto'),
            'max_execution_time' => env('OCTANE_MAX_EXECUTION_TIME', 30),
            'max_request_size' => env('OCTANE_MAX_REQUEST_SIZE', 10485760),
            'options' => [
                'log_file' => storage_path('logs/swoole_http.log'),
                'package_max_length' => 10 * 1024 * 1024,
                'buffer_output_size' => 10 * 1024 * 1024,
                'socket_buffer_size' => 128 * 1024 * 1024,
                'max_coroutine' => 100000,
                'send_yield' => true,
                'reload_async' => true,
                'max_wait_time' => 60,
                'enable_reuse_port' => true,
                'enable_coroutine' => true,
                'http_compression' => true,
                'http_compression_level' => 6,
                'compression_min_length' => 20,
            ],
        ],

        'roadrunner' => [
            'host' => env('OCTANE_HOST', '0.0.0.0'),
            'port' => env('OCTANE_PORT', 8000),
            'rpc_port' => env('OCTANE_RPC_PORT', 6001),
            'workers' => env('OCTANE_WORKERS', 'auto'),
            'max_execution_time' => env('OCTANE_MAX_EXECUTION_TIME', 30),
        ],

        'frankenphp' => [
            'host' => env('OCTANE_HOST', '0.0.0.0'),
            'port' => env('OCTANE_PORT', 8000),
            'workers' => env('OCTANE_WORKERS', 'auto'),
            'max_execution_time' => env('OCTANE_MAX_EXECUTION_TIME', 30),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Warm / Flush Bindings
    |--------------------------------------------------------------------------
    |
    | The bindings listed below will either be pre-warmed when a worker boots
    | or they will be flushed before every request. Flushing a binding will
    | force the container to resolve that binding again when asked for it.
    |
    */

    'warm' => [
        'auth',
        'auth.driver',
        'blade.compiler',
        'cache',
        'cache.store',
        'config',
        'db',
        'db.factory',
        'encrypter',
        'files',
        'hash',
        'hash.driver',
        'log',
        'mail.manager',
        'queue',
        'queue.connection',
        'redis',
        'redis.connection',
        'router',
        'session',
        'session.store',
        'translator',
        'url',
        'validator',
        'view',
        'view.engine.resolver',
    ],

    'flush' => [
        'auth.password',
        'auth.password.broker',
        'cookie',
        'request',
        'view.factory',
    ],

    /*
    |--------------------------------------------------------------------------
    | Octane Cache Table
    |--------------------------------------------------------------------------
    |
    | While using Octane, you may leverage the Octane cache, which is powered
    | by a Swoole table. You may set the maximum number of rows as well as
    | the number of bytes per row using the configuration options below.
    |
    */

    'cache' => [
        'rows' => 1000,
        'bytes' => 10000,
    ],

    /*
    |--------------------------------------------------------------------------
    | Octane Listeners
    |--------------------------------------------------------------------------
    |
    | All of the event listeners for Octane's events are defined below. These
    | listeners are responsible for resetting your application's state so
    | that each request is handled by a fresh application instance.
    |
    */

    'listeners' => [

        WorkerStarting::class => [
            EnsureUploadedFilesAreValid::class,
            EnsureUploadedFilesCanBeMoved::class,
        ],

        RequestReceived::class => [
            ...Octane::prepareApplicationForNextOperation(),
            ...Octane::prepareApplicationForNextRequest(),
            //
        ],

        RequestHandled::class => [
            //
        ],

        RequestTerminated::class => [
            FlushTemporaryContainerInstances::class,
        ],

        TaskReceived::class => [
            ...Octane::prepareApplicationForNextOperation(),
        ],

        TaskTerminated::class => [
            //
        ],

        TickReceived::class => [
            ...Octane::prepareApplicationForNextOperation(),
        ],

        TickTerminated::class => [
            //
        ],

        WorkerErrorOccurred::class => [
            ReportException::class,
            StopWorkerIfNecessary::class,
        ],

        WorkerStopping::class => [
            //
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Warm / Flush Bindings Automatically
    |--------------------------------------------------------------------------
    |
    | Instead of explicitly listing bindings to warm and flush, you may set
    | the following option to "true" to have Octane automatically warm and
    | flush bindings for you. Bindings will be determined by their type.
    |
    */

    'auto_reload' => env('OCTANE_AUTO_RELOAD', false),

    /*
    |--------------------------------------------------------------------------
    | Watch
    |--------------------------------------------------------------------------
    |
    | The following list of files and directories will be watched when using
    | the --watch option offered by Octane. If any of the directories and
    | files are changed, Octane will automatically reload your workers.
    |
    */

    'watch' => [
        'app',
        'bootstrap',
        'config',
        'database',
        'resources/**/*.php',
        'routes',
        '.env',
    ],

    /*
    |--------------------------------------------------------------------------
    | Garbage Collection Threshold
    |--------------------------------------------------------------------------
    |
    | When executing long-running tasks, memory leaks may be an issue. To
    | assist with preventing memory leaks, Octane can restart workers if
    | their memory usage exceeds a given threshold. You may disable it.
    |
    */

    'garbage_collection' => [
        'threshold' => 50, // MB
    ],

    /*
    |--------------------------------------------------------------------------
    | Maximum Execution Time
    |--------------------------------------------------------------------------
    |
    | The following setting configures the maximum execution time for requests
    | being handled by Octane. You may set this value to 0 to indicate that
    | there should not be a time limit on Octane request execution time.
    |
    */

    'max_execution_time' => env('OCTANE_MAX_EXECUTION_TIME', 30),

];
