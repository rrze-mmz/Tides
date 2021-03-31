<?php

namespace Tests;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @param null $user
     * @return Collection|Model|mixed
     */
    protected function signIn($user = null): mixed
    {
        $user = $user ?: User::factory()->create();

        $this->actingAs($user);

        return $user;
    }

    protected function signInAdmin($user = null)
    {
        $user = $user ?: User::factory()->create();

        $user->assignRole('admin');

        $this->actingAs($user);

        return $user;
    }
}
