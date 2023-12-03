<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class HttpRequestService
{
    /**
     * @param  string  $url
     * @param  array|null  $postParams
     * @param  array  $headers
     * @return Response
     * @throws Throwable
     */
    public function post(string $url, array $postParams = null, array $headers = []): Response
    {
        try {
            return Http::withHeaders($headers)->post($url, $postParams);
        } catch (Throwable $exception) {
            Log::error(sprintf('[%s:%s] %s', __CLASS__, __FUNCTION__, $exception->getMessage()), [
                'postParams' => $postParams,
                'url' => $url,
                'exception_message' => $exception->getMessage(),
                'exception_trace' => substr($exception->getTraceAsString(), 0, 500),
            ]);

            // Although we are logging the exception,
            // we should still throw it to be handled in the higher level
            $exceptionClass = get_class($exception);
            throw new $exceptionClass($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}
