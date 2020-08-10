<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. A "local" driver, as well as a variety of cloud
    | based drivers are available for your choosing. Just store away!
    |
    | Supported: "local", "s3", "rackspace"
    |
    */

    'default' => env('DEFAULT_FILESYSTEM', 'digital-ocean'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('DEFAULT_CLOUD_FILESYSTEM', 'digital-ocean'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root'   => storage_path('app'),
            'url'    => '/storage/app',
        ],

        's3' => [
            'driver' => 's3',
            'key'    => env('S3_KEY', 'digital-ocean'),
            'secret' => env('S3_SECRET', 'digital-ocean'),
            'region' => env('S3_REGION', 'digital-ocean'),
            'bucket' => env('S3_BUCKET', 'digital-ocean'),
        ],

        'digital-ocean' => [
            'driver'   => 'do_spaces',
            'endpoint' => env('DO_ENDPOINT', ''),
            'key'      => env('DO_KEY', ''),
            'secret'   => env('DO_SECRET', ''),
            'region'   => env('DO_REGION', ''),
            'space'   => env('DO_SPACE', '')
        ],

        'rackspace' => [
            'driver'    => 'rackspace',
            'username'  => env('RACKSPACE_USERNAME', ''),
            'key'       => env('RACKSPACE_KEY', ''),
            'container' => env('RACKSPACE_CONTAINER', ''),
            'endpoint'  => env('RACKSPACE_ENDPOINT', ''),
            'region'    => env('RACKSPACE_REGION', ''),
        ],

    ],

];
