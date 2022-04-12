<?php

namespace Tests\Feature\Backend;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManageDevicesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_simple_user_is_not_allowed_to_manage_devices(): void
    {
        $this->get(route('devices.index'))->assertRedirect(route('login'));

        $this->signInRole('user');

        $this->get(route('devices.index'))->assertForbidden();
    }

    /** @test */
    public function a_moderator_is_not_allowed_to_manage_devices(): void
    {
        $this->signInRole('moderator');

        $this->get(route('devices.index'))->assertForbidden();
    }

    /** @test */
    public function an_assistant_is_allowed_to_manage_devices(): void
    {
        $this->signInRole('assistant');

        $this->get(route('devices.index'))->assertOk();
    }

    /** @test */
    public function an_admin_is_allowed_to_manage_devices(): void
    {
        $this->signInRole('admin');

        $this->get(route('devices.index'))
            ->assertOk()
            ->assertViewHas('devices')
            ->assertViewIs('backend.devices.index');
    }

    /** @test */
    public function it_shows_a_devices_datatable_on_index_page(): void
    {
        $this->signInRole('assistant');

        $this->get(route('devices.index'))->assertSeeLivewire('devices-data-table');
    }
}
