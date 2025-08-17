<?php

namespace App\Listeners;

use App\Events\UserCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\SendPhoneVerificationNotification;

class SendUserVerificationNotifications implements ShouldQueue
{
    public function handle(UserCreated $event): void
    {
        $user = $event->user;

        if (!empty($user->phone)) {
            $user->notify(new SendPhoneVerificationNotification());
        }

        if (!$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
        }
    }
}
