<?php

namespace App\Saloon\Middlewares;

use Illuminate\Support\Facades\Log;
use Saloon\Contracts\ResponseMiddleware;
use Saloon\Http\Response;

class SaloonLogger implements ResponseMiddleware
{
    public function __invoke(Response $response): void
    {
        Log::channel('saloon')->info('Saloon Logger: ' . $response->getPsrRequest()->getUri()->getPath(), [
            'request' => [
                'method' => $response->getPsrRequest()->getMethod(),
                'body' => json_decode($response->getPsrRequest()->getbody(), 1),
            ],
            'response' => [
                'status' => (string) $response->status(),
                'body' =>  json_decode($response->body(), true) !== null ? $response->json() : $response->body(),
            ],
        ]);
    }
}
