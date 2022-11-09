<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class UnlockObject extends Component
{
    public $model;

    public $showModal = false;

    public $password;

    /**
     * Unlock form validation rules
     *
     * @return array[]
     */
    public function rules(): array
    {
        return [
            'password' => ['required', Password::min(8)->mixedCase()],
        ];
    }

    /**
     * Livewire render function for the component
     *
     * @return Factory|View|Application
     */
    public function render(): Factory|View|Application
    {
        return view('livewire.unlock-object');
    }

    public function unlock()
    {
        $this->validate();

        if ($this->model->password === $this->password) {
            $client = 'password';

            $token = getAccessToken($this->model, $time = dechex(time()), $client, false);
            setSessionAccessToken($this->model, $token, $time, $client);

            return redirect(route('frontend.series.show', $this->model));
        } else {
            $this->addError('password', 'Unlock password ist wrong');
        }
    }
}
