<?php

namespace App\Livewire;

use App\Notifications\UserSubscribed;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class SubscribeSection extends Component
{
    public $series;

    public $formAction;

    public $isUserSubscribed;

    public $user;

    public $btnText;

    public function mount(): void
    {
        $this->user = auth()->user();
        $this->isUserSubscribed = $this->user->subscriptions()->where('series_id', $this->series->id)->exists();
        $this->formAction = ($this->isUserSubscribed) ? 'unsubscribe' : 'subscribe';
        $this->btnText = ($this->isUserSubscribed) ? 'Unsubscribe' : 'Subscribe';
    }

    /**
     * Subscribe logged-in user to series
     */
    public function subscribe(): void
    {
        $delay = now()->addMinutes(1);
        $this->user->subscriptions()->attach($this->series);
        $this->isUserSubscribed = true;
        $this->formAction = 'unsubscribe';
        $this->btnText = 'unsubscribe';

        $this->user->notify((new UserSubscribed($this->series))->delay($delay));
    }

    /**
     * Unsubscribe logged-in user from series
     */
    public function unsubscribe(): void
    {
        $this->user->subscriptions()->detach($this->series);
        $this->isUserSubscribed = false;
        $this->formAction = 'subscribe';
        $this->btnText = 'Subscribe';
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.subscribe-section');
    }
}
