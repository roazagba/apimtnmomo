<?php
return [
    // The base URL for the MTN MoMo API, defaulting to the sandbox environment if not set in the .env file
    'host' => env('RA_BASE_URL', 'https://sandbox.momodeveloper.mtn.com/'),

    // The default currency used for transactions, set to 'EUR' unless overridden by the environment
    'currency' => env('RA_CURRENCY', 'EUR'),

    // The target environment, typically 'sandbox' for development or 'mtnmbenin' for production if it's MTN MoMo Benin
    'target' => env('RA_TARGET_ENVIRONNEMENT', 'sandbox'),

    // The callback URL for receiving notifications about transaction statuses
    'callback_url' => env('RA_CALLBACK_URL', 'http://localhost:8000'),

    // Collection product configurations
    'collection' => [
        // API key secret used for authentication with the MoMo collection API
        'api_key_secret' => env('RA_COLLECTION_API_KEY_SECRET', 'df7c71c3c5ac4d3e9433daf43a6e2987'),

        // Primary subscription key provided by MTN to authenticate API requests
        'primary_key' => env('RA_COLLECTION_PRIMARY_KEY', '57ca5f1907074bf590090041688d781d'),

        // The user ID associated with the MoMo collection account
        'user_id' => env('RA_COLLECTION_USER_ID', 'd9097d11-90f4-411c-8c28-b2f97ad7ef61')
    ]
];
