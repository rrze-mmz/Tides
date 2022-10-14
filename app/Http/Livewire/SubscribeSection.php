<?php

namespace App\Http\Livewire;

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

    /**
     * @param $id
     */
    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->user = auth()->user();
    }

    /**
     * @return void
     */
    public function mount(): void
    {
        $this->isUserSubscribed = $this->user->subscriptions()->where('series_id', $this->series->id)->exists();
        $this->formAction = ($this->isUserSubscribed) ? 'unsubscribe' : 'subscribe';
        $this->btnText = ($this->isUserSubscribed) ? 'Unsubscribe' : 'Subscribe';
    }

    /**
     * Subscribe logged-in user to series
     *
     * @return void
     */
    public function subscribe(): void
    {
        $this->user->subscriptions()->attach($this->series);
        $this->isUserSubscribed = true;
        $this->formAction = 'unsubscribe';
        $this->btnText = 'unsubscribe';
    }

    /**
     * Unsubscribe logged-in user from series
     *
     * @return void
     */
    public function unsubscribe(): void
    {
        $this->user->subscriptions()->detach($this->series);
        $this->isUserSubscribed = false;
        $this->formAction = 'subscribe';
        $this->btnText = 'Subscribe';
    }

    /**
     * @return Factory|View|Application
     */
    public function render(): Factory|View|Application
    {
        return view('livewire.subscribe-section');
    }
}
