<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'These credentials do not match our records.',
    'password' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
    'register' => [
        'success' => 'Successfully created user! Please login to continue',
        'failed' => 'Failed to create user, email already exists',
        'error' => 'Error creating user. Please try again'
    ],
    'login' => [
        'success' => 'Successfully logged in!',
        'failed' => 'Incorrect email or password',
        'error' => 'Error logging in. Please try again',
    ],
    'logout' => [
        'success' => 'User logged out successfully',
    ],
];
