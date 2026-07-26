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
    | NANOSPARK_EMAIL=nanospark46@gmail.com
    | NANOSPARK_LINKEDIN=https://www.linkedin.com/in/nano-spark-4300a23bb
    | NANOSPARK_YOUTUBE=https://youtube.com/@nanosparkbytes
    | NANOSPARK_FACEBOOK=https://www.facebook.com/profile.php?id=61592291288490
    | NANOSPARK_INSTAGRAM=https://www.instagram.com/nano_spark_
    |
    */

    'email' => env('NANOSPARK_EMAIL', 'nanospark46@gmail.com'),

    'social' => [
        'linkedin' => env('NANOSPARK_LINKEDIN', 'https://www.linkedin.com/in/nano-spark-4300a23bb'),
        'youtube' => env('NANOSPARK_YOUTUBE', 'https://youtube.com/@nanosparkbytes'),
        'facebook' => env('NANOSPARK_FACEBOOK', 'https://www.facebook.com/profile.php?id=61592291288490'),
        'instagram' => env('NANOSPARK_INSTAGRAM', 'https://www.instagram.com/nano_spark_'),
    ],

];
