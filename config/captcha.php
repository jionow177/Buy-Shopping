<?php

return [
    'secret' => env('NOCAPTCHA_SECRET'),
    'sitekey' => env('NOCAPTCHA_SITEKEY'),
    'options' => [
        'theme' => 'custom',
        'custom_theme' => [
            'use_dark_theme' => true, // or false
            'main_color' => '#FF0000', // replace with your desired color
        ],
        'timeout' => 30,
    ],
];
