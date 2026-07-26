<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Nano Spark Contact & Social Media
    |--------------------------------------------------------------------------
    |
    | Update these values in your .env file to configure social media links
    | and contact information used across the public site.
    |
    | NANOSPARK_EMAIL=hello@nanospark.org
    | NANOSPARK_LINKEDIN=https://linkedin.com/company/nanospark
    | NANOSPARK_YOUTUBE=https://youtube.com/@nanospark
    | NANOSPARK_FACEBOOK=https://facebook.com/nanospark
    |
    */

    'email' => env('NANOSPARK_EMAIL', 'hello@nanospark.org'),

    'social' => [
        'linkedin' => env('NANOSPARK_LINKEDIN', 'https://linkedin.com/company/nanospark'),
        'youtube' => env('NANOSPARK_YOUTUBE', 'https://youtube.com/@nanospark'),
        'facebook' => env('NANOSPARK_FACEBOOK', 'https://facebook.com/nanospark'),
    ],

];
