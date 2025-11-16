<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cloudflare Turnstile Site Key
    |--------------------------------------------------------------------------
    |
    | This is your Turnstile site key (public key) from the Cloudflare dashboard.
    | Get your keys at: https://dash.cloudflare.com/?to=/:account/turnstile
    |
    */
    'site_key' => env('TURNSTILE_SITE_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Cloudflare Turnstile Secret Key
    |--------------------------------------------------------------------------
    |
    | This is your Turnstile secret key (private key) from the Cloudflare dashboard.
    | NEVER expose this in client-side code.
    |
    */
    'secret_key' => env('TURNSTILE_SECRET_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Test Mode
    |--------------------------------------------------------------------------
    |
    | When enabled, Turnstile validation will be skipped. Useful for testing.
    | You can also use Cloudflare's test keys instead:
    | Site Key (always passes): 1x00000000000000000000AA
    | Secret Key (always passes): 1x0000000000000000000000000000000AA
    |
    */
    'test_mode' => env('TURNSTILE_TEST_MODE', false),

    /*
    |--------------------------------------------------------------------------
    | Expected Action
    |--------------------------------------------------------------------------
    |
    | If set, the validation will verify that the token was generated for
    | this specific action. Leave null to skip action verification.
    |
    */
    'expected_action' => env('TURNSTILE_EXPECTED_ACTION', null),

    /*
    |--------------------------------------------------------------------------
    | Expected Hostname
    |--------------------------------------------------------------------------
    |
    | If set, the validation will verify that the token was generated for
    | this specific hostname. Leave null to skip hostname verification.
    |
    */
    'expected_hostname' => env('TURNSTILE_EXPECTED_HOSTNAME', null),

    /*
    |--------------------------------------------------------------------------
    | Default Theme
    |--------------------------------------------------------------------------
    |
    | Default theme for Turnstile widgets. Options: 'light', 'dark', 'auto'
    |
    */
    'theme' => env('TURNSTILE_THEME', 'auto'),

    /*
    |--------------------------------------------------------------------------
    | Default Size
    |--------------------------------------------------------------------------
    |
    | Default size for Turnstile widgets.
    | Options: 'normal' (300x65px), 'compact' (150x140px), 'flexible' (100% width)
    |
    */
    'size' => env('TURNSTILE_SIZE', 'normal'),

    /*
    |--------------------------------------------------------------------------
    | Default Language
    |--------------------------------------------------------------------------
    |
    | Default language for Turnstile widgets. Use 'auto' for automatic detection
    | or specify an ISO 639-1 language code (e.g., 'en', 'es', 'fr')
    |
    */
    'language' => env('TURNSTILE_LANGUAGE', 'auto'),
];
