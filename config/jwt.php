<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Asymmetric Signing Key Filename
    |--------------------------------------------------------------------------
    |
    | File name for RSA key file used to sign JWT tokens.
    |
    */

    'signing_key_filename' => env('JWT_SIGNING_KEY_FILENAME', 'jwt-signing-key.pem'),

    /*
    |--------------------------------------------------------------------------
    | Verification Key string
    |--------------------------------------------------------------------------
    |
    | Verification used to verify JWT tokens.
    |
    */

    'verification_key' => env('JWT_VERIFICATION_KEY'),

];
