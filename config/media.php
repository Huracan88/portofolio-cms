<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Media disk & directory
    |--------------------------------------------------------------------------
    */

    'disk' => env('MEDIA_DISK', 'public'),

    'directory' => env('MEDIA_DIRECTORY', 'media'),

    /*
    |--------------------------------------------------------------------------
    | Upload constraints
    |--------------------------------------------------------------------------
    */

    'max_upload_kb' => env('MEDIA_MAX_UPLOAD_KB', 5120),

    'allowed_mimes' => [
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/gif',
        'image/avif',
    ],

    /*
    |--------------------------------------------------------------------------
    | Processing defaults
    |--------------------------------------------------------------------------
    */

    'webp_quality' => env('MEDIA_WEBP_QUALITY', 80),

    'default_max_width' => env('MEDIA_DEFAULT_MAX_WIDTH', 2400),

    /*
    |--------------------------------------------------------------------------
    | Crop presets (ratio as float, null means free)
    |--------------------------------------------------------------------------
    */

    'presets' => [
        'cover' => [
            'name' => 'cover',
            'label' => '16:9 Cover',
            'ratio' => 16 / 9,
            'aspect' => '16:9',
        ],
        'standard' => [
            'name' => 'standard',
            'label' => '4:3 Standard',
            'ratio' => 4 / 3,
            'aspect' => '4:3',
        ],
        'square' => [
            'name' => 'square',
            'label' => '1:1 Square',
            'ratio' => 1,
            'aspect' => '1:1',
        ],
        'free' => [
            'name' => 'free',
            'label' => 'Free',
            'ratio' => null,
            'aspect' => null,
        ],
    ],

];
