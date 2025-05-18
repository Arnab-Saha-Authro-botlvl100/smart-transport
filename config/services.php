<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // 'bkash' => [
    //     'sandbox' => env('BKASH_SANDBOX', true),
    //     'app_key' => env('BKASH_APP_KEY'),
    //     'app_secret' => env('BKASH_APP_SECRET'),
    //     'username' => env('BKASH_USERNAME'),
    //     'password' => env('BKASH_PASSWORD'),
    //     'callback_url' => env('BKASH_CALLBACK_URL'),
    // ],
    'bkash' => [
        'sandbox' => env('BKASH_SANDBOX', false), // Changed default to false for production
        'app_key' => env('BKASH_APP_KEY'),
        'app_secret' => env('BKASH_APP_SECRET'),
        'username' => env('BKASH_USERNAME'),
        'password' => env('BKASH_PASSWORD'),
        'callback_url' => env('BKASH_CALLBACK_URL'),
        'timeout' => 30, // Added timeout
        'aws_region' => 'ap-southeast-1', // Must match bKash's region
        'service_name' => 'execute-api', // Critical for SigV4
    ],
];
