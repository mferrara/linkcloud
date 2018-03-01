<?php

return [

    /*
    |--------------------------------------------------------------------------
    | LinkCloud Settings
    |--------------------------------------------------------------------------
    |
    |
    |
    */

    'api_token'             => env('LINKCLOUD_API_TOKEN', null),
    'default_link_count'    => env('DEFAULT_LINK_COUNT', 3),
    'seeded_user_count'     => env('SEEDED_USER_COUNT', 100)

];
