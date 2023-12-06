<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\OtpTypeEnum;
use App\Services\OtpRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{

    public function __construct(private readonly OtpRequestService $otpRequestService)
    {

    }

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     * @param  LoginRequest  $request
     * @return RedirectResponse|null
     * @throws ValidationException
     */
    public function store(LoginRequest $request): ?RedirectResponse
    {
        if ($request->checkAuthenticate()) {
            $user = User::where('email', $request->input('email'))->first();
            $otpRequest = $this->otpRequestService->request($user, OtpTypeEnum::EMAIL);
            if($otpRequest || $user->otp_number) {
                return redirect()->route('auth.otp.create', ['email' => $request->input('email')]);
            }else{
                throw ValidationException::withMessages([
                    'email' => 'Something went wrong while logging you in.',
                ]);
            }
        }
        return null;
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
