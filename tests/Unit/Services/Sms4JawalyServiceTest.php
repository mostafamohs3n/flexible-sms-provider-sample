<?php

namespace Tests\Unit\Services;

use App\Services\Sms\Sms4JawalyService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class Sms4JawalyServiceTest extends TestCase
{
    public function testSendSuccess()
    {
        $baseUrl = config('sms.4jawaly.baseUrl');

        $message = 'Test message';
        $phoneNumber = '9999999998123';

        $sms4JawalyService = $this->app->make(Sms4JawalyService::class);
        // Mock Http::post to return a fake response for success
        Http::fake([
            $baseUrl.Sms4JawalyService::SEND_SMS_ENDPOINT =>
                Http::response(['code' => 200], 200),
        ]);

        $result = $sms4JawalyService->send($message, $phoneNumber);

        // Assertion
        $this->assertTrue($result);
    }

    public function testSendErrorHttpCode()
    {
        $baseUrl = config('sms.4jawaly.baseUrl');
        // Arrange
        $sentMessage = 'Test message';
        $phoneNumber = '1234567890'; // Replace with a valid phone number
        $sender = 'SenderName'; // Replace with a valid sender name
        $authToken = 'your_auth_token'; // Replace with a valid authorization token

        $sms4JawalyService = $this->app->make(Sms4JawalyService::class);

        // Mock Http::post to return a fake response for an error
        Http::fake([
            $baseUrl.Sms4JawalyService::SEND_SMS_ENDPOINT =>
                Http::response([], 500),
        ]);

        // Mock Log::error
        Log::shouldReceive('error')
           ->once()
           ->withArgs(function ($message, $context) use ($phoneNumber, $sentMessage) {
               return
                   strpos($message,
                       sprintf('[%s] An error occurred while sending a message', Sms4JawalyService::class)) !== false &&
                   $context['message'] == $sentMessage &&
                   $context['phone_number'] === $phoneNumber;
           })
        ;

        $result = $sms4JawalyService->send($sentMessage, $phoneNumber);

        // Assert
        $this->assertFalse($result);
    }

    public function testSendErrorTokenInvalid()
    {
        $baseUrl = config('sms.4jawaly.baseUrl');
        // Arrange
        $sentMessage = 'Test message';
        $receivedMessage = 'التوكن منتهى او انة غير مفعل';
        $phoneNumber = '1234567890'; // Replace with a valid phone number

        $sms4JawalyService = $this->app->make(Sms4JawalyService::class);

        // Mock Http::post to return a fake response for an error
        Http::fake([
            $baseUrl.Sms4JawalyService::SEND_SMS_ENDPOINT =>
                Http::response(['message' => $receivedMessage, 'code' => 400], 400),
        ]);

        // Mock Log::error
        Log::shouldReceive('error')
           ->once()
           ->withArgs(function ($message, $context) use ($phoneNumber, $sentMessage) {
               return
                   strpos($message,
                       sprintf('[%s] An error occurred while sending a message', Sms4JawalyService::class)) !== false &&
                   $context['message'] == $sentMessage &&
                   $context['phone_number'] === $phoneNumber;
           })
        ;

        $result = $sms4JawalyService->send($sentMessage, $phoneNumber);

        $this->assertFalse($result);
    }

    public function testSendErrorInvalidNumber()
    {
        $baseUrl = config('sms.4jawaly.baseUrl');

        $sentMessage = 'Test message';
        $phoneNumber = '9999999998123';
        $receivedMessage = 'لا يوجد ارقام متاحة';

        $sms4JawalyService = $this->app->make(Sms4JawalyService::class);

        // Mock Http::post to return a fake response for an error
        Http::fake([
            $baseUrl.Sms4JawalyService::SEND_SMS_ENDPOINT =>
                Http::response(['messages' => ['err_text' => $receivedMessage]], 200),
        ]);

        $result = $sms4JawalyService->send($sentMessage, $phoneNumber);

        $this->assertFalse($result);
    }
}

