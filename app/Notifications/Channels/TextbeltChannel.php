<?php

namespace App\Notifications\Channels;

use Illuminate\Support\Facades\Cache;
use Illuminate\Notifications\Notification;
use Propaganistas\LaravelPhone\PhoneNumber;
use App\Saloon\Connectors\TextbeltConnector;
use App\Saloon\Requests\GenerateTextbeltOTP;
use App\Notifications\Contracts\TextbeltMessage;

class TextbeltChannel
{
    public function send($notifiable, Notification $notification): void
    {
        $apiKey = (string) config('services.textbelt.key', '');
        if ($apiKey === '') {
            return;
        }

        $payload = $notification instanceof TextbeltMessage
            ? (array) $notification->toTextbelt($notifiable)
            : [];

        $phone = $payload['phone'] ?? ($notifiable->routeNotificationFor('textbelt') ?? null);

        if (empty($phone)) {
            return;
        }

        $phone = new PhoneNumber($phone, config('app.country_code'))->formatE164();
        $userId = (string) ($payload['userId'] ?? $notifiable->getKey());

        $connector = new TextbeltConnector();
        $request = new GenerateTextbeltOTP($phone, $userId, $apiKey);
        $response = $connector->send($request);
        $data = $response->json();

        if (!empty($data['success']) && isset($data['otpId'])) {
            Cache::put("otp_id_{$phone}", $data['otpId'], now()->addMinutes(5));
        }
    }
}
