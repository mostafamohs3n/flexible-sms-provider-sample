<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\OtpRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class OtpController extends Controller
{
    public function create(Request $request)
    {
        // if email isn't sent, skip it
        if (!$request->input('email')) {
            return redirect()->back();
        }
        return view('auth.otp', [
            'email' => $request->input('email'),
        ]);
    }

    public function store(OtpRequest $request)
    {
        $email = $request->input('email');
        $otp = $request->integer('otp');


        if ($user = User::where(['email' => $email, 'otp_number' => $otp])->first()) {
            $request->authenticate($user);
            $request->session()->regenerate();
            return redirect()->intended(RouteServiceProvider::HOME);
        } else {
            throw ValidationException::withMessages([
                'otp' => 'Incorrect OTP',
            ]);
        }
    }
}
