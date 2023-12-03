<?php

use App\Services\Sms\Sms4JawalyService;

return [
    //general config
    'config' => [
        'current' => env('SLLM_SMS_PROVIDER', '4jawaly'),
    ],
    '4jawaly' => [
        'provider' => Sms4JawalyService::class,
        'baseUrl' => env('SMS_4JAWALY_API_URL', 'https://api-sms.4jawaly.com/api/v1/'),
        'apiKey' => env('SMS_4JAWALY_API_KEY'),
        'apiSecret' => env('SMS_4JAWALY_API_SECRET'),
        'sender' => env('SMS_4JAWALY_SENDER'),
    ],
    'twilio' => [
        'provider' => 'non-existent-class',
    ],
];
