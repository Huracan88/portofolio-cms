<?php

return [

    /*
    |--------------------------------------------------------------------------
    | OpenRouter API
    |--------------------------------------------------------------------------
    |
    | The API key is intentionally NOT stored here; it lives encrypted in the
    | settings table (see App\Models\Setting) so it can be managed from the
    | admin panel without touching the environment.
    */

    'base_uri' => env('OPENROUTER_BASE_URI', 'https://openrouter.ai/api/v1'),

    'translate_timeout' => (int) env('OPENROUTER_TRANSLATE_TIMEOUT', 30),

    'image_timeout' => (int) env('OPENROUTER_IMAGE_TIMEOUT', 120),

    'referer' => env('OPENROUTER_REFERER', config('app.url')),

    'app_title' => env('OPENROUTER_APP_TITLE', 'Portfolio CMS'),

    /*
    | Fallback models, used when the settings row is missing.
    */

    'translate_model' => 'google/gemini-3.1-flash',

    'image_model' => 'google/gemini-3.0-nano-banana',

];
