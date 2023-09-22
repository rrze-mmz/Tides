<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class UnlockObject extends Component
{
    public $showModal = false;

    public $password;

    public $model;

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

    public function mount($model): void
    {
        $this->model = $model;
    }

    /**
     * Livewire render function for the component
     */
    public function render(): Factory|View|Application
    {
        return view('livewire.unlock-object', [
            'model' => $this->model,
        ]);
    }

    /**
     * Unlock an object with password
     *
     * @return Application|RedirectResponse|Redirector|void
     */
    public function unlock()
    {
        $this->validate();

        if ($this->model->password === $this->password) {
            $client = 'password';

            $token = getAccessToken($this->model, $time = dechex(time()), $client);
            setSessionAccessToken($this->model, $token, $time, $client);

            return redirect(route('frontend.series.show', $this->model));
        } else {
            $this->addError('password', 'Unlock password ist wrong');
        }
    }
}
