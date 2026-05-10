<?php

return [

    /*
    |--------------------------------------------------------------------------
    |  Access Token
    |--------------------------------------------------------------------------
    | the token expires in minutes
    |
    */
    'access_token' => [
        'expiresAt' => 600,
    ],


    /*
   |--------------------------------------------------------------------------
   |  Refresh Token
   |--------------------------------------------------------------------------
   | the token expires in days
   |
   */
    'refresh_token' => [
        'expiresAt' => 30,
    ],

    /*
 |--------------------------------------------------------------------------
 |  Password Hasher Driver
 |--------------------------------------------------------------------------
 | the algorithm to hash and check the password in system
 |
 */
    'password_hash_driver' => env('PASSWORD_HASH_DRIVER', 'bcrypt'),

    'password_hashers' => [
        'bcrypt' => \Modules\Authentication\Infrastructure\Services\Security\LaravelPasswordHasher::class,
        'md5' => \Modules\Authentication\Infrastructure\Services\Security\MD5PasswordHasher::class,
    ],
];
