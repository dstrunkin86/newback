<?php

return [
    'extra_width' => env('DELIVERY_EXTRA_WIDTH'),
    'extra_height' => env('DELIVERY_EXTRA_HEIGHT'),
    'extra_depth' => env('DELIVERY_EXTRA_DEPTH'),
    'extra_weight' => env('DELIVERY_EXTRA_WEIGHT'),
    'sdek' => [
        'account' => env('SDEK_ACCOUNT'),
        'secure_password' => env('SDEK_SECURE_PASSWORD'),
        'api_url' => env('SDEK_API_URL'),
    ]

];
