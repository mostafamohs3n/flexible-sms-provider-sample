<?php

namespace App\Services;

use App\Interfaces\SmsProvider;

class SmsService
{
    public function __construct(private readonly SmsProvider $smsProvider)
    {}

    public function sendSms($message, $phoneNumber){
        $this->smsProvider->send($message, $phoneNumber);
    }
}
