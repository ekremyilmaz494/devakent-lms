<?php

return [
    'institution_name' => env('LMS_INSTITUTION_NAME', 'Devakent Hastanesi'),
    'institution_subtitle' => env('LMS_INSTITUTION_SUBTITLE', 'Hastanesi LMS'),
    'timezone' => env('LMS_TIMEZONE', 'Europe/Istanbul'),
    'default_language' => env('LMS_LANGUAGE', 'tr'),

    'exam' => [
        'default_duration' => env('LMS_EXAM_DURATION', 30), // dakika
        'default_passing_score' => env('LMS_PASSING_SCORE', 70), // 0-100
        'default_max_attempts' => env('LMS_MAX_ATTEMPTS', 3),
        'shuffle_questions' => env('LMS_SHUFFLE_QUESTIONS', true),
    ],

    'session' => [
        'timeout' => env('LMS_SESSION_TIMEOUT', 120), // dakika
        'max_login_attempts' => env('LMS_MAX_LOGIN_ATTEMPTS', 5),
    ],
];
