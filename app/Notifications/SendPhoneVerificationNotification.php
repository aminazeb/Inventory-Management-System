<?php

namespace App\Notifications;

use App\Notifications\Channels\TextbeltChannel;
use App\Notifications\Contracts\TextbeltMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SendPhoneVerificationNotification extends Notification implements ShouldQueue, TextbeltMessage
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return [TextbeltChannel::class];
    }

    public function toTextbelt(object $notifiable): array
    {
        return [
            'phone' => $notifiable->routeNotificationFor('textbelt'),
            'userId' => (string) $notifiable->getKey(),
        ];
    }
}
