<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param User $user
     * @return void
     */
    public function created(User $user)
    {
        $user->assignRole('user');
        session()->flash('flashMessage', $user->getFullNameAttribute() . ' ' . __FUNCTION__ . ' successfully');
    }

    /**
     * Handle the User "updated" event.
     *
     * @param User $user
     * @return void
     */
    public function updated(User $user)
    {
        session()->flash('flashMessage', $user->getFullNameAttribute() . ' ' . __FUNCTION__ . ' successfully');
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param User $user
     * @return void
     */
    public function deleted(User $user)
    {
        session()->flash('flashMessage', $user->getFullNameAttribute() . ' ' . __FUNCTION__ . ' successfully');
    }

    /**
     * Handle the User "restored" event.
     *
     * @param User $user
     * @return void
     */
    public function restored(User $user)
    {
        session()->flash('flashMessage', $user->getFullNameAttribute() . ' ' . __FUNCTION__ . ' successfully');
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param User $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        session()->flash('flashMessage', $user->getFullNameAttribute() . ' ' . __FUNCTION__ . ' successfully');
    }
}
