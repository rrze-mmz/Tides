<?php

use App\Http\Livewire\SubscribeSection;
use App\Notifications\UserSubscribed;
use Facades\Tests\Setup\SeriesFactory;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;

it('sends a mail notification if user hits subscription button', function () {
    $user = signIn();

    Notification::fake();
    Queue::fake();

    $series = SeriesFactory::withClips(1)->withAssets(3)->create();

    Livewire::test(SubscribeSection::class, [
        'series' => $series,
    ])->call('subscribe');

    Notification::assertSentTo([$user], UserSubscribed::class);
});
