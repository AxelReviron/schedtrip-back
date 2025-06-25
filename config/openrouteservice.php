<?php

return [

    'baseUrl' => env('ORS_BASE_URL', 'https://api.openrouteservice.org'),

    'apiKey' => env('ORS_API_KEY', ''),

    'rate_limiter_without_api_key' => [
        'geocode' => [
            'per_minute' => [
                'max_attempts' => 10,
                'decay_seconds' => 60,
            ],
            'per_day' => [
                'max_attempts' => 100,
                'decay_seconds' => 60 * 60 * 24,
            ],
        ],
        'directions' => [
            'per_minute' => [
                'max_attempts' => 4,
                'decay_seconds' => 60,
            ],
            'per_day' => [
                'max_attempts' => 200,
                'decay_seconds' => 60 * 60 * 24,
            ],
        ],
    ],

    'rate_limiter_with_api_key' => [
        'geocode' => [
            'per_minute' => [
                'max_attempts' => 100,
                'decay_seconds' => 60,
            ],
            'per_day' => [
                'max_attempts' => 1000,
                'decay_seconds' => 60 * 60 * 24,
            ],
        ],
        'directions' => [
            'per_minute' => [
                'max_attempts' => 40,
                'decay_seconds' => 60,
            ],
            'per_day' => [
                'max_attempts' => 2000,
                'decay_seconds' => 60 * 60 * 24,
            ],
        ],
    ]
];
