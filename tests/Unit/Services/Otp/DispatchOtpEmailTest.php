<?php

namespace Tests\Unit\Services\Otp;

use App\Mail\UserOtpMail;
use App\Models\User;
use App\Services\Otp\DispatchOtpEmail;
use Faker\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class DispatchOtpEmailTest extends TestCase
{

    public function testDispatch()
    {
        Mail::fake();

        $faker = Factory::create();
        $user = User::create([
            'name' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'phone_number' => $faker->phoneNumber(),
            'password' => Hash::make('Test1234'),
            'otp_number' => '99999',
            'otp_expiration_date' => '2023-12-05 11:00:00',
        ]);

        $dispatcher = new DispatchOtpEmail($user);

        $dispatcher->dispatch();

        Mail::assertSent(UserOtpMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }
}
