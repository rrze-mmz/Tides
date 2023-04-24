<?php

namespace Tests\Feature\Http\Controllers\Backend;

use App\Enums\Role;
use App\Models\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivitiesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_visitor_is_is_not_allowed_to_view_activities_index(): void
    {
        $this->get(route('activities.index'))->assertRedirect(route('login'));
    }

    /** @test */
    public function a_logged_in_user_is_not_allowed_to_view_activities_index(): void
    {
        $this->signInRole(Role::USER);

        $this->get(route('activities.index'))->assertForbidden();
    }

    /** @test */
    public function a_moderator_is_not_allowed_to_view_activities_index(): void
    {
        $this->signInRole(Role::MODERATOR);

        $this->get(route('activities.index'))->assertForbidden();
    }

    /** @test */
    public function an_admin_is_allowed_to_view_activities_index(): void
    {
        Activity::factory(3)->create();
        $this->signInRole(Role::ADMIN);

        $this->get(route('activities.index'))->assertOk();
    }
}
