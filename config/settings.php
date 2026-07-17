<?php

return [
    /*
    |--------------------------------------------------------------------------
    | General Application Settings
    |--------------------------------------------------------------------------
    |
    | Centralized settings for the application.
    |
    */

    'pagination' => [
        'default' => 15,
    ],

    'rate_limits' => [
        'orders' => 3, // Max 3 orders per minute per IP
    ],

    'allowed_keys' => [
        'siteName',
        'siteDescription',
        'contactEmail',
        'contactPhone',
        'whatsappNumber',
        'facebook',
        'twitter',
        'instagram',
        'snapchat',
        'tiktok',
        'theme',
        'language'
    ],
];
