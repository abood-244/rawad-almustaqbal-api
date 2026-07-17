<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Feature Flags
    |--------------------------------------------------------------------------
    |
    | Use these flags to selectively enable or disable external integrations.
    | This is crucial for keeping local development completely isolated
    | from production services like Cloudinary, external SMS, or Analytics.
    |
    */

    'cloudinary' => env('ENABLE_CLOUDINARY', false),
    'email'      => env('ENABLE_EMAIL', false),
    'analytics'  => env('ENABLE_ANALYTICS', false),
    'whatsapp'   => env('ENABLE_WHATSAPP', false),
];
