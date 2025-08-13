<?php

namespace App\Saloon\Connectors;

use Saloon\Http\Connector;
use App\Saloon\Middlewares\SaloonLogger;
use Saloon\Traits\Body\HasJsonBody;

class TextbeltConnector extends Connector
{
    public function __construct()
    {
        $this->middleware()->onResponse(new SaloonLogger());
    }

    public function resolveBaseUrl(): string
    {
        return 'https://textbelt.com';
    }
}
