<?php

namespace App\Services;

use App\Events\OtpRequest;
use App\Models\User;
use Carbon\Carbon;

class OtpRequestService
{
    /**
     * @param  User  $user
     * @param  string  $type
     * @return bool
     */
    public function request(User $user, string $type)
    {
        if($this->shouldSendOtp($user)) {
            OtpRequest::dispatch($user, $type);
            return true;
        }
        return false;
    }

    /**
     * @param  User  $user
     * @return bool
     */
    private function shouldSendOtp(User $user): bool
    {
        if(empty($user->otp_number)){
            return true;
        }
        $otpExpirationDate = $user->otp_expiration_date ? Carbon::parse($user->otp_expiration_date) : null;
        if($otpExpirationDate instanceof Carbon && $otpExpirationDate->isPast() || empty($otpExpirationDate)){
            return true;
        }
        return false;
    }
}
