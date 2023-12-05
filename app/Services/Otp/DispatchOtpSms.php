<?php

namespace App\Services\Otp;

use App\Interfaces\OtpDispatcher;
use App\Interfaces\SmsProvider;
use App\Models\User;

class DispatchOtpSms implements OtpDispatcher
{

    public function __construct(
        private readonly User $user,
        private readonly SmsProvider $smsProvider
    ) {
    }

    public function dispatch(): bool
    {
        return $this->smsProvider->send(
            message: $this->getMessage(),
            phoneNumber: $this->user->phone_number
        );
    }

    private function getMessage()
    {
        return sprintf("Your requested OTP at %s is %s ", config('app.name'), $this->user->otp_number);
    }
}
