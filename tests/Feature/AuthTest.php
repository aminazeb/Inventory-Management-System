<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Events\UserCreated;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Listeners\SendUserVerificationNotifications;
use App\Notifications\SendPhoneVerificationNotification;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_user_verification_notifications_listener()
    {
        Event::fake();
        Notification::fake();
        $user = User::factory()->create(['email_verified_at' => null, 'phone' => '+1234567890']);

        $event = new UserCreated($user);
        (new SendUserVerificationNotifications())->handle($event);

        Notification::assertSentTo($user, SendPhoneVerificationNotification::class);
        $this->assertFalse($user->hasVerifiedEmail());
    }
}
