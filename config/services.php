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
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'firebase' => [
        "apiKey" => "AIzaSyACt4uHahV9GiJ9RcaClgBTHbMdPPk4zEY",
        "authDomain" => "sotnmt-app.firebaseapp.com",
        "projectId" => "sotnmt-app",
        "storageBucket" => "sotnmt-app.appspot.com",
        "messagingSenderId" => "567958870918",
        "appId" => "1:567958870918:web:e6083a98df80542a642a07",
        "measurementId" => "G-S4HZQ498JH",
    ],

];
