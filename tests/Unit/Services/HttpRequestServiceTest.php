<?php

namespace Tests\Unit\Services;

use App\Services\HttpRequestService;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class HttpRequestServiceTest extends TestCase
{


    public function testPostSuccess()
    {
        $url = 'https://sllm.sa/api';
        $postParams = ['message' => 'TestMessage'];
        $headers = ['Authorization' => 'Basic {Base64String}'];

        // Intercept Http::post to return a fake response
        Http::fake([
            $url => Http::response(['status' => 'success'], 200),
        ]);

        $httpService = new HttpRequestService();
        $response = $httpService->post($url, $postParams, $headers);

        // Assertions
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->status());
        $this->assertEquals(['status' => 'success'], $response->json());
        Http::assertSent(function ($request) use ($url, $postParams, $headers) {
            return $request->url() === $url &&
                $request->data() === $postParams &&
                array_intersect(array_keys($request->headers()), array_keys(array_intersect($headers))) &&
                $request->method() === 'POST';
        });
    }

    public function testPostExceptionHandling()
    {
        // Arrange
        $url = 'https://sllm.sa/api';
        $postParams = ['message' => 'TestMessage'];
        $headers = ['Authorization' => 'Basic {Base64String}'];
        $exceptionMessage = 'Failed to do an HTTP Request';

        // Mock Http::post to throw an exception
        Http::fake([
            $url => function ($request) use ($exceptionMessage) {
                throw new \Exception($exceptionMessage, 500);
            },
        ]);



        // Mock Log::error
        Log::shouldReceive('error')
           ->once()
           ->withArgs(function ($message, $context) use ($url, $postParams) {
               return strpos($message, '['.HttpRequestService::class.':post]') !== false &&
                   $context['url'] === $url &&
                   $context['postParams'] === $postParams &&
                   isset($context['exception_message']) &&
                   isset($context['exception_trace']);
           })
        ;

        // Assertions
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage($exceptionMessage);

        $httpService = new HttpRequestService();
        $httpService->post($url, $postParams, $headers);
    }
}
