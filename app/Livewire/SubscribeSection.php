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
        // If coming back from terms acceptance, directly attempt to subscribe
        if (session()->has('redirect_back_to_subscribe')) {
            $this->subscribe();
        }

        $this->isUserSubscribed = $this->user->subscriptions()->where('series_id', $this->series->id)->exists();
        $this->formAction = ($this->isUserSubscribed) ? 'unsubscribe' : 'subscribe';
        $this->btnText = ($this->isUserSubscribed) ? __('common.unsubscribe') : __('common.subscribe');
    }

    /**
     * Subscribe logged-in user to series
     */
    public function subscribe()
    {
        if (! $this->user->settings->data['accept_use_terms']) {
            session(['redirect_back_to_subscribe' => $this->series->id]);

            return redirect()->to(route('frontend.userSettings.edit'));
        }
        $delay = now()->addMinutes(1);
        $this->user->subscriptions()->attach($this->series);
        $this->isUserSubscribed = true;
        $this->formAction = 'unsubscribe';
        $this->btnText = __('common.unsubscribe');
        session()->remove('redirect_back_to_subscribe');
        $this->user->notify((new UserSubscribed($this->series))->delay($delay));
    }

    /**
     * Unsubscribe logged-in user from series
     */
    public function unsubscribe()
    {
        $this->user->subscriptions()->detach($this->series);
        $this->isUserSubscribed = false;
        $this->formAction = 'subscribe';
        $this->btnText = __('common.subscribe');
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.subscribe-section');
    }
}
