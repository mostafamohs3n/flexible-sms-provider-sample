<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\OtpTypeEnum;
use App\Services\OtpRequestService;

class OtpController extends Controller
{

    public function __construct(private readonly OtpRequestService $otpRequestService)
    {

    }

    public function store()
    {
        $user = User::find(1);
        dump(
            $this->otpRequestService->request($user, OtpTypeEnum::SMS)
        );
    }
}
