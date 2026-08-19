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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'midtrans' => [
        'server_key' => env('MIDTRANS_SERVER_KEY'),
        'client_key' => env('MIDTRANS_CLIENT_KEY'),
        'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
        'sandbox' => env('MIDTRANS_IS_PRODUCTION', false) === false,
    ],

    'fonnte' => [
        'token' => env('FONNTE_API_KEY'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI', '/customer/auth/google/callback'),
    ],

    'whatsapp_gateway' => [
        'url' => env('WA_SERVER_URL', 'http://127.0.0.1:3000'),
    ],

    'cooca' => [
        'url' => env('COOCA_URL', 'https://cooca.id'),
        'secret' => env('COOCA_SECRET', 'cooca-license-shared-secret-key-2026'),
    ],

    'hostinger' => [
        'api_token' => env('HOSTINGER_API_TOKEN'),
        'api_url' => env('HOSTINGER_API_URL', 'https://developers.hostinger.com/api'),
        'usd_to_idr_rate' => env('HOSTINGER_USD_TO_IDR_RATE', 16000),
    ],

];

