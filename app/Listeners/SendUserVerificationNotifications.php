<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Notifications\SendPhoneVerificationNotification;

class SendUserVerificationNotifications
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
