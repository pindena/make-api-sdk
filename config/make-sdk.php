<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Make API Credentials
    |--------------------------------------------------------------------------
    |
    | The Make API uses HTTP Basic Authentication. The username is your
    | Userid and the password is your APIKey, which can be generated
    | from the Make dashboard.
    |
    */

    'username' => env('MAKE_API_USERNAME', ''),
    'password' => env('MAKE_API_PASSWORD', ''),

    /*
    |--------------------------------------------------------------------------
    | API Base URLs
    |--------------------------------------------------------------------------
    |
    | The Make API is split into two separate services: one for subscriber
    | management and one for newsletter management. You may override
    | these URLs if you need to point to a different environment.
    |
    */

    'subscribersApiUrl' => env('MAKE_SUBSCRIBERS_API_URL', 'https://subscribers.dialogapi.no/api/public/v2'),
    'newslettersApiUrl' => env('MAKE_NEWSLETTERS_API_URL', 'https://newsletters.dialogapi.no/api/public/v1'),

];
