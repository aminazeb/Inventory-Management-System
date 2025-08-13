<?php

namespace App\Saloon\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Contracts\Body\HasBody;
use Saloon\Traits\Body\HasJsonBody;

class GenerateTextbeltOTP extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $phone,
        protected string $userId,
        protected string $apiKey,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/otp/generate';
    }

    protected function defaultBody(): array
    {
        return [
            'phone' => urlencode($this->phone),
            'userid' => $this->userId,
            'key' => $this->apiKey,
        ];
    }
}
