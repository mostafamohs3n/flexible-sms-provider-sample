<?php

namespace Tests\Unit\Services\Otp;

use App\Interfaces\SmsProvider;
use App\Models\User;
use App\Services\Otp\DispatchOtpSms;
use App\Services\Sms\Sms4JawalyService;
use Faker\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DispatchOtpSmsTest extends TestCase
{

    public function testDispatch()
    {
        $faker = Factory::create();
        $user = User::create([
            'name' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'phone_number' => $faker->phoneNumber(),
            'password' => Hash::make('Test1234'),
            'otp_number' => '99999',
            'otp_expiration_date' => '2023-12-05 11:00:00',
        ]);

        $dispatcher = new DispatchOtpSms($user, $this->app->make(SmsProvider::class));

        Http::fake([
            config('sms.4jawaly.baseUrl').Sms4JawalyService::SEND_SMS_ENDPOINT =>
                Http::response(['code' => 200], 200),
        ]);
        
        $results = $dispatcher->dispatch();
        $this->assertTrue($results);
    }
}
