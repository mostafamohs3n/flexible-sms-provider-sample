<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\OtpTypeEnum;
use App\Services\OtpRequestService;
use Faker\Factory;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OtpRequestServiceTest extends TestCase
{

    public function testOtpRequestSuccess()
    {
        /** @var OtpRequestService $otpRequestService */
        $otpRequestService = $this->app->make(OtpRequestService::class);
        $faker = Factory::create();
        $user = User::create([
            'name' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'phone_number' => $faker->phoneNumber(),
            'password' => Hash::make('Test1234'),
        ]);

        $result = $otpRequestService->request($user, OtpTypeEnum::EMAIL);
        $this->assertTrue($result);

        $user->otp_expiration_date = now()->subMinutes(3)->format('Y-m-d H:i:s');
        $user->save();

        $result = $otpRequestService->request($user, OtpTypeEnum::SMS);
        $this->assertTrue($result);

        $user = $user->refresh();
        $this->assertNotNull($user->otp_number);
        $this->assertNotNull($user->otp_expiration_date);
    }


    public function testOtpRequestOtpNotExpired()
    {
        /** @var OtpRequestService $otpRequestService */
        $otpRequestService = $this->app->make(OtpRequestService::class);
        $faker = Factory::create();
        $user = User::create([
            'name' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'phone_number' => $faker->phoneNumber(),
            'password' => Hash::make('Test1234'),
            'otp_number' => '99999',
            'otp_expiration_date' => '2023-12-05 11:00:00',
        ]);

        $result = $otpRequestService->request($user, OtpTypeEnum::EMAIL);
        $this->assertFalse($result);

        // make sure user data is not changed.
        $user = $user->refresh();
        $this->assertEquals('99999', $user->otp_number);
        $this->assertEquals('2023-12-05 11:00:00', $user->otp_expiration_date);
    }
}
