<?php

namespace App\Services\Otp;

use App\Interfaces\OtpDispatcher;
use App\Mail\UserOtpMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class DispatchOtpEmail implements OtpDispatcher
{

    public function __construct(private readonly User $user)
    {
    }

    public function dispatch(): bool
    {
        return (bool) Mail::to($this->user->email)->send(new UserOtpMail($this->user));
    }
}
