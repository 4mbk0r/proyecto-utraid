<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Broadcaster
    |--------------------------------------------------------------------------
    |
    | This option controls the default broadcaster that will be used by the
    | framework when an event needs to be broadcast. You may set this to
    | any of the connections defined in the "connections" array below.
    |
    | Supported: "pusher", "ably", "redis", "log", "null"
    |
    */

    'default' => env('redis', 'redis'),

    /*
    |--------------------------------------------------------------------------
    | Broadcast Connections
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the broadcast connections that will be used
    | to broadcast events to other systems or over websockets. Samples of
    | each available type of connection are provided inside this array.
    |
    */

    'connections' => [

        'socketio' => [
            'driver' => 'socketio',
            'host' => '127.0.0.1', //env('SOCKETIO_HOST'),
            'port' => 3000, //env('SOCKETIO_PORT'),
            'scheme' => 'http', //env('SOCKETIO_SCHEME'),
            'client' => 'socket.io-client',
            //'auth_token' => env('SOCKETIO_AUTH_TOKEN'),
        ],
        'pusher' => [
            'driver' => 'pusher',
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'app_id' => env('PUSHER_APP_ID'),
            'id' => env('PUSHER_APP_ID'),
            
            'options' => [
                'cluster' => 'us2',
                'useTLS' => true
            ],
        ],

        'ably' => [
            'driver' => 'ably',
            'key' => env('ABLY_KEY'),
        ],
        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
            'host' => '172.31.217.96',
            'port' => 6379,
        ],
        /*
        'redis' => [
            'driver' => 'redis',
            'connection' => 'predis',
            'host' => '172.31.217.96',
            'port' => 6379,
            'password' => null,
        ],*/

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],

    ],

];
