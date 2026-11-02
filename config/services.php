<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
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
    'khqr' => [
    'account_id'     => env('BAKONG_ACCOUNT_ID'),
    'merchant_name'  => env('KHQR_MERCHANT_NAME'),
    'merchant_city'  => env('KHQR_MERCHANT_CITY'),
    'api_token'      => env('BAKONG_API_TOKEN'),
    'p2p_ref'        => env('KHQR_P2P_REF'),
    'p2p_account_1'  => env('KHQR_P2P_ACCOUNT_1'),
    'p2p_account_2'  => env('KHQR_P2P_ACCOUNT_2'),
    'account_number' => env('KHQR_ACCOUNT_NUMBER'),
    'bank_name'      => env('KHQR_BANK_NAME', 'ABA Bank'),
],

];
