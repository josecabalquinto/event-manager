<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google Maps Configuration
    |--------------------------------------------------------------------------
    |
    | Configure Google Maps API settings for the event manager application.
    | You'll need to obtain an API key from Google Cloud Console.
    |
    */

    'google' => [
        'api_key' => env('GOOGLE_MAPS_API_KEY', ''),
        'default_center' => [
            'lat' => env('GOOGLE_MAPS_DEFAULT_LAT', 14.5995), // Manila, Philippines
            'lng' => env('GOOGLE_MAPS_DEFAULT_LNG', 120.9842),
        ],
        'default_zoom' => env('GOOGLE_MAPS_DEFAULT_ZOOM', 13),
    ],
];
