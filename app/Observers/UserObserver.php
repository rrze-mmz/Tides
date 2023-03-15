<?php

namespace App\Observers;

use App\Models\User;
use App\Services\ElasticsearchService;

class UserObserver
{
    public function __construct(private ElasticsearchService $elasticsearchService)
    {
    }

    /**
     * Handle the User "created" event.
     *
     * @return void
     */
    public function created(User $user)
    {
        $user->assignRole('user');
        session()->flash('flashMessage', "{$user->getFullNameAttribute()} ".__FUNCTION__.' successfully');

        $this->elasticsearchService->createIndex($user);
    }

    /**
     * Handle the User "updated" event.
     *
     * @return void
     */
    public function updated(User $user)
    {
        session()->flash('flashMessage', "{$user->getFullNameAttribute()} ".__FUNCTION__.' successfully');

        $this->elasticsearchService->updateIndex($user);
    }

    /**
     * Handle the User "deleted" event.
     *
     * @return void
     */
    public function deleted(User $user)
    {
        session()->flash('flashMessage', "{$user->getFullNameAttribute()} ".__FUNCTION__.' successfully');

        $this->elasticsearchService->deleteIndex($user);
    }
}
