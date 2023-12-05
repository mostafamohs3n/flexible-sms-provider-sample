<x-mail::message>
# {{config('app.name')}} - Your One Time Password

Hello {{$user->name ?? ''}}

You have recently requested a one time password to your account. Please find it below.

Please do not share this to anyone, if you did not request this OTP, please contact us.


<div style="background: #FAFAFA; text-align:center;margin: 10px 0; font-size: 1.3em;font-weight: bold;color: #000; padding: 10px; letter-spacing: 3px; border: 1px solid #F9F9F9; font-size: 1.3em;">
{{$user->otp_number}}
</div>


Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
