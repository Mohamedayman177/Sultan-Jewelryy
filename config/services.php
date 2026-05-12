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

    'whatsapp' => [
        'number' => env('WHATSAPP_NUMBER', '966538600000'),
    ],

    'myfatoorah' => [
        'base_url' => env('MYFATOORAH_BASE_URL', 'https://apitest.myfatoorah.com'),
        'api_key' => env('MYFATOORAH_API_KEY'),
        'timeout' => (int) env('MYFATOORAH_TIMEOUT', 45),
        'placeholder_email' => env('MYFATOORAH_PLACEHOLDER_EMAIL', 'payments@sultan-jewelry.local'),
        /** HTTPS عام (مثل ngrok) — MyFatoorah ترفض localhost في CallBackUrl/ErrorUrl */
        'public_app_url' => env('MYFATOORAH_PUBLIC_APP_URL'),
        /** بعد إعادة التوجيه من البوابة قد تبقى الفاتورة Pending لثوانٍ — إعادة الاستعلام */
        'status_poll_attempts' => max(1, (int) env('MYFATOORAH_STATUS_POLL_ATTEMPTS', 20)),
        'status_poll_delay_ms' => max(0, (int) env('MYFATOORAH_STATUS_POLL_DELAY_MS', 500)),
    ],

];
