<?php
return [
    'host' => env('RA_BASE_URL', 'https://sandbox.momodeveloper.mtn.com/'),
    'currency' => env('RA_CURRENCY', 'EUR'),
    'target' => env('RA_TARGET_ENVIRONNEMENT', 'sandbox'),
    'callbackUrl' => env('RA_CALLBACK_URL', 'http://localhost:8000'),
    'collectionApiKeySecret' => env('RA_COLLECTION_API_KEY_SECRET', 'df7c71c3c5ac4d3e9433daf43a6e2987'),
    'collectionPrimaryKey' => env('RA_COLLECTION_PRIMARY_KEY', '57ca5f1907074bf590090041688d781d'),
    'collectionUserId' => env('RA_COLLECTION_USER_ID', 'd9097d11-90f4-411c-8c28-b2f97ad7ef61')
];
