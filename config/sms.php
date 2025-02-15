<?php


return [

    /*
    |--------------------------------------------------------------------------
    | Сервіс SMS-сповіщень
    |--------------------------------------------------------------------------
    |
    | Тут визначається, який сервіс використовується для надсилання SMS.
    | Наприклад: "twilio", "nexmo", "smsc".
    |
    */
    'default' => env('SMS_DRIVER', 'twilio'),

    /*
    |--------------------------------------------------------------------------
    | Налаштування сервісів SMS
    |--------------------------------------------------------------------------
    |
    | Доступні провайдери та їхні ключі API, номери відправника тощо.
    |
    */

    'services' => [

        'twilio' => [
            'sid' => env('TWILIO_SID'),
            'token' => env('TWILIO_TOKEN'),
            'from' => env('TWILIO_FROM'),
        ],

        'smsc' => [
            'login' => env('SMSC_LOGIN'),
            'password' => env('SMSC_PASSWORD'),
            'from' => env('SMSC_FROM', 'MyApp'),
        ],

    ],

];
