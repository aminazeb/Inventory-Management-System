<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Events\UserCreated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SendPhoneVerificationNotification;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_user_verification_notifications_listener()
    {
        Notification::fake();
        $user = User::factory()->create(['email_verified_at' => null, 'phone' => '+1234567890']);
        event(new UserCreated($user));
        Notification::assertSentTo($user, SendPhoneVerificationNotification::class);
        $this->assertFalse($user->hasVerifiedEmail());
    }
}
