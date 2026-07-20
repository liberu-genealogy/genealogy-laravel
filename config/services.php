<?php

declare(strict_types=1);

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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'facial_recognition' => [
        // Defaults to 'none': MockProvider invents faces and person matches, and it
        // is the only provider that exists (aws/azure are commented out in
        // FacialRecognitionService). Defaulting to 'mock' meant every install
        // fabricated photo tags. Set FACIAL_RECOGNITION_PROVIDER=mock explicitly if
        // you want simulated output for a demo.
        'provider' => env('FACIAL_RECOGNITION_PROVIDER', 'none'),
        'aws' => [
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'collection_id' => env('AWS_REKOGNITION_COLLECTION_ID', 'genealogy-faces'),
        ],
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT_URI', env('APP_URL').'/oauth/facebook/callback'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI', env('APP_URL').'/oauth/google/callback'),
        // Google Meet conferencing (GoogleMeetService) — without these the
        // platform stays disabled rather than unreachable.
        'refresh_token' => env('GOOGLE_REFRESH_TOKEN'),
        'enabled' => env('GOOGLE_MEET_ENABLED', false),
    ],

    'twitter' => [
        'client_id' => env('TWITTER_CLIENT_ID'),
        'client_secret' => env('TWITTER_CLIENT_SECRET'),
        'redirect' => env('TWITTER_REDIRECT_URI', env('APP_URL').'/oauth/twitter/callback'),
    ],

    'google_vision' => [
        'api_key' => env('GOOGLE_VISION_API_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Video Conferencing
    |--------------------------------------------------------------------------
    |
    | ZoomService and TeamsService read these keys; they existed nowhere in the
    | config, so those platforms could not be enabled at all without editing
    | source. Declared here so an administrator can configure them via env. An
    | unset key leaves the platform not-configured (it degrades to Unavailable),
    | not unreachable.
    |
    */

    // Credential keys default to '' (not null): ZoomService/TeamsService assign
    // them to typed string properties via config(key, ''), whose default only
    // fires when the key is ABSENT — now that the key is present, an unset env
    // must resolve to '' rather than null or construction TypeErrors.
    'zoom' => [
        'enabled' => env('ZOOM_ENABLED', false),
        'base_url' => env('ZOOM_BASE_URL', 'https://api.zoom.us/v2'),
        'key' => env('ZOOM_KEY', ''),
        'secret' => env('ZOOM_SECRET', ''),
        'account_id' => env('ZOOM_ACCOUNT_ID', ''),
    ],

    'teams' => [
        'enabled' => env('TEAMS_ENABLED', false),
        'client_id' => env('TEAMS_CLIENT_ID', ''),
        'client_secret' => env('TEAMS_CLIENT_SECRET', ''),
        'tenant_id' => env('TEAMS_TENANT_ID', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Genealogy Service Providers
    |--------------------------------------------------------------------------
    |
    | Configuration for external genealogy service integrations like
    | MyHeritage, Ancestry, and FamilySearch for record matching.
    |
    */

    'myheritage' => [
        'api_key' => env('MYHERITAGE_API_KEY'),
        'base_url' => env('MYHERITAGE_BASE_URL', 'https://api.myheritage.com/v1'),
        'timeout' => env('MYHERITAGE_TIMEOUT', 30),
    ],

    'ancestry' => [
        'api_key' => env('ANCESTRY_API_KEY'),
        'base_url' => env('ANCESTRY_BASE_URL', 'https://api.ancestry.com/v1'),
        'timeout' => env('ANCESTRY_TIMEOUT', 30),
    ],

    'familysearch' => [
        'api_key' => env('FAMILYSEARCH_API_KEY'),
        'base_url' => env('FAMILYSEARCH_BASE_URL', 'https://api.familysearch.org/platform'),
        'timeout' => env('FAMILYSEARCH_TIMEOUT', 30),
    ],

];
