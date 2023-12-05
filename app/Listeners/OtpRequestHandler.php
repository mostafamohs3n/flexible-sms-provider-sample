<?php

namespace App\Listeners;

use App\Events\OtpRequest;
use App\Interfaces\SmsProvider;
use App\OtpTypeEnum;
use App\Services\Otp\DispatchOtpEmail;
use App\Services\Otp\DispatchOtpSms;
use Illuminate\Support\Facades\Log;

class OtpRequestHandler
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * @param  OtpRequest  $event
     * @return void
     */
    public function handle(OtpRequest $event): void
    {
        $user = $event->getUser();
        $type = $event->getOtpType();
        $user->otp_number = $this->generateOtp();
        $user->otp_expiration_date = now()->addMinutes(env('OTP_EXPIRATION_MINUTES', 10));
        if(!$user->save()){
            Log::error(sprintf('[%s:%s] Failed to generate new OTP code', __CLASS__, __FUNCTION__));
            return;
        }
        if ($type == OtpTypeEnum::EMAIL) {
            $dispatcher = new DispatchOtpEmail($user);
        } else if ($type == OtpTypeEnum::SMS) {
            $dispatcher = new DispatchOtpSms($user, app(SmsProvider::class));
        } else {
            Log::warning(sprintf('[%s:%s] Invalid Otp Type supplied.', __CLASS__, __FUNCTION__), [
                'user_id' => $user->id,
                'type' => $type,
            ]);
            return;
        }
        $dispatcher->dispatch();
    }

    /**
     * @return int
     */
    private function generateOtp(): int
    {
        return rand(10000, 999999);
    }
}
