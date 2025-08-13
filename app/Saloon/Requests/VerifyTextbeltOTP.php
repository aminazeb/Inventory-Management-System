<?php

namespace App\Saloon\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class VerifyTextbeltOTP extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $otp,
        protected string $userId,
        protected string $apiKey,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/otp/verify';
    }

    protected function defaultQuery(): array
    {
        return [
            'otp' => $this->otp,
            'userid' => $this->userId,
            'key' => $this->apiKey,
        ];
    }
}
