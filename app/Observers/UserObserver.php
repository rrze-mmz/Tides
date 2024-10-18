<?php

namespace App\Observers;

use App\Enums\Role;
use App\Models\Setting;
use App\Models\User;
use App\Services\OpenSearchService;

class UserObserver
{
    public function __construct(private OpenSearchService $openSearchService) {}

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $user->assignRole(Role::USER);
        $user->settings()->create([
            'name' => $user->username,
            'data' => config('settings.user'), ]);

        session()->flash('flashMessage', "{$user->getFullNameAttribute()} ".__FUNCTION__.' successfully');

        $this->openSearchService->createIndex($user);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        session()->flash('flashMessage', "{$user->getFullNameAttribute()} ".__FUNCTION__.' successfully');

        $this->openSearchService->updateIndex($user);
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        Setting::where('name', $user->username)->delete();
        session()->flash('flashMessage', "{$user->getFullNameAttribute()} ".__FUNCTION__.' successfully');

        $this->openSearchService->deleteIndex($user);
    }
}
