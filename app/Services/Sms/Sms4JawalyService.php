<?php

namespace App\Services\Sms;

use App\Interfaces\SmsProvider;
use App\Services\HttpRequestService;
use Illuminate\Support\Facades\Log;

class Sms4JawalyService implements SmsProvider
{

    const SEND_SMS_ENDPOINT = 'account/area/sms/send';

    private string $baseUrl;
    private string $sender;

    public function __construct(private readonly HttpRequestService $httpService)
    {
        $this->sender = config('sms.4jawaly.sender');
        $this->baseUrl = config('sms.4jawaly.baseUrl');
    }

    /**
     * @param  string  $message
     * @param  string|array  $phoneNumber
     * @return bool
     */
    public function send(string $message, string|array $phoneNumber): bool
    {
        try {
            $headers = [
                'Authorization' => $this->getAuth(),
            ];
            $payload = [
                'messages' => [
                    [
                        'text' => $message,
                        'numbers' => is_array($phoneNumber) ? $phoneNumber : [$phoneNumber],
                        'sender' => $this->sender,
                    ]
                ]
            ];
            Log::channel('sms')->info(sprintf('[%s:%s] Initiating sending a message.', __CLASS__, __FUNCTION__), [
                'message' => $message,
                'phone_number' => $phoneNumber,
                'sender' => $this->sender,
            ]);
            $response = $this->httpService->post(
                $this->baseUrl.self::SEND_SMS_ENDPOINT,
                $payload,
                $headers
            );
            $responseJson = $response->json();
            if($this->isRequestSuccessful($response)){
                //@TODO: Maybe log in database.
                Log::channel('sms')->info(sprintf('[%s:%s] Message sent successfully.', __CLASS__, __FUNCTION__), [
                    'message' => $message,
                    'phone_number' => $phoneNumber,
                    'sender' => $this->sender,
                ]);
                return true;
            }else{
                Log::channel('sms')->error(sprintf('[%s:%s] Something went wrong while sending a message.', __CLASS__, __FUNCTION__), [
                    'message' => $message,
                    'phone_number' => $phoneNumber,
                    'sender' => $this->sender,
                    'error_message' => $responseJson['messages'][0]['err_text'] ?? $responseJson['message'] ?? "Error",
                    'response' => json_encode($responseJson),
                ]);
            }
        } catch (\Throwable $exception) {
            Log::error(sprintf('[%s] An error occurred while sending a message', __CLASS__), [
                'exception_message' => $exception->getMessage(),
                'exception_trace' => substr($exception->getTraceAsString(), 0, 500),
                'message' => $message,
                'phone_number' => $phoneNumber,
            ]);
        }
        return false;
    }

    /**
     * @param $response
     * @return bool
     */
    private function isRequestSuccessful($response): bool
    {
        $responseJson = $response->json();
        return
            $response->successful()
            && $responseJson['code'] == 200
            && empty($responseJson['messages'][0]['err_text']);
    }

    /**
     * @return string
     */
    private function getAuth(): string
    {
        $apiKey = config('sms.4jawaly.apiKey');
        $apiSecret = config('sms.4jawaly.apiSecret');
        return 'Basic ' . base64_encode($apiKey.':'.$apiSecret);
    }
}
