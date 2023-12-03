<?php

namespace App\Http\Controllers;

use App\Interfaces\SmsProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class SMSController extends Controller
{
    public function __construct(private readonly SmsProvider $smsProvider)
    {
    }

    public function create(){
        return view('send-sms');
    }

    public function store(Request $request){
        try {
            $this->validate($request, [
                'phoneNumber' => ['required', 'numeric', 'min:9'],
                'message' => ['required', 'string', 'min:2'],
            ]);
        } catch (ValidationException $e){
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        if($this->smsProvider->send($request->input('message'), $request->input('phoneNumber'))){
            return redirect()->back()->withInput()->with('success', "SMS Sent successfully!");
        }else{
            return redirect()->back()->withInput()->with('error', "Failed to send SMS, please contact administrators.");
        }
    }
}
