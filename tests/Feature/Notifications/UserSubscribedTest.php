<?php

namespace Tests\Feature\Notifications;

use App\Http\Livewire\SubscribeSection;
use App\Notifications\UserSubscribed;
use Facades\Tests\Setup\SeriesFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;

class UserSubscribedTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_sends_a_mail_notification_if_user_hits_subscription_button(): void
    {
        $user = $this->signIn();

        Notification::fake();
        Queue::fake();

        $series = SeriesFactory::withClips(1)->withAssets(3)->create();

        Livewire::test(SubscribeSection::class, [
            'series' => $series,
        ])->call('subscribe');

        Notification::assertSentTo([$user], UserSubscribed::class);
    }
}
