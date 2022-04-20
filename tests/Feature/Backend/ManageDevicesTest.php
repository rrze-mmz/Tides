<?php

namespace Tests\Feature\Backend;

use App\Http\Livewire\DevicesDataTable;
use App\Models\Device;
use App\Models\DeviceLocation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
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

        $device = Device::factory()->create();

        $this->get(route('devices.index'))->assertForbidden();
        $this->get(route('devices.create'))->assertForbidden();
        $this->get(route('devices.edit', $device))->assertForbidden();
        $this->post(route('devices.store'), ['name' => 'test'])->assertForbidden();
        $this->get(route('devices.edit', $device))->assertForbidden();
        $this->put(route('devices.update', $device), ['name' => 'test'])->assertForbidden();
        $this->delete(route('devices.destroy', $device))->assertForbidden();

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

    /** @test */
    public function it_shows_a_create_device_button_on_index_page(): void
    {
        $this->signInRole('assistant');

        $this->get(route('devices.index'))->assertSee('Create a new device');
    }

    /** @test */
    public function it_shows_an_edit_device_button_on_index_page(): void
    {
        $this->signInRole('assistant');

        Device::factory()->create(['name' => 'Exon DMP 351']);

        $this->get(route('devices.index'))->assertSee('Edit');
    }

    /** @test */
    public function it_shows_a_delete_device_button_on_index_page(): void
    {
        $this->signInRole('assistant');

        Device::factory()->create(['name' => 'Exon DMP 351']);

        $this->get(route('devices.index'))->assertSee('Delete');
    }

    /** @test */
    public function it_can_search_for_device_name_in_index_data_table(): void
    {
        $recDevice1 = Device::factory()->create(['name' => 'Exon DMP 351']);
        $recDevice2 = Device::factory()->create(['name' => 'Wolf 300']);

        Livewire::test(DevicesDataTable::class)
            ->set('search', 'Exon')
            ->assertSee($recDevice1->name)
            ->assertDontSee($recDevice2->name);
    }

    /** @test */
    public function it_can_search_for_device_location_in_index_data_table(): void
    {
        $medBuilding = DeviceLocation::factory()->create(['name' => 'Medical Center']);
        $computerScienceBuidling = DeviceLocation::factory()->create(['name' => 'Computer Science Building']);

        $recDevice1 = Device::factory()->create([
            'name'        => 'Exon DMP 351',
            'location_id' => $medBuilding->id
        ]);
        $recDevice2 = Device::factory()->create([
            'name'        => 'Wolf 300',
            'location_id' => $computerScienceBuidling->id
        ]);

        Livewire::test(DevicesDataTable::class)
            ->set('search', 'medical')
            ->assertSee($recDevice1->name)
            ->assertDontSee($recDevice2->name);
    }

    /** @test */
    public function an_assistant_can_view_device_create_form(): void
    {
        $this->signInRole('assistant');

        $this->get(route('devices.create'))
            ->assertOk()
            ->assertViewIs('backend.devices.create')
            ->assertSee('Device name')
            ->assertSee('Location')
            ->assertSee('Opencast device name')
            ->assertSee('URL')
            ->assertSee('Camera URL')
            ->assertSee('Power outlet URL')
            ->assertSee('IP Address')
            ->assertSee('Recording available')
            ->assertSee('Livestream available')
            ->assertSee('Hybrid')
            ->assertSee('Operational')
            ->assertSee('Description')
            ->assertSee('Comment')
            ->assertSee('Telephone number');
    }

    /** @test */
    public function it_requires_a_name_when_creating_a_device(): void
    {
        $this->signInRole('assistant');

        $this->post(route('devices.store'), Device::factory()->raw(['name' => '']))
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_requires_an_existing_location_id_when_creating_a_device(): void
    {
        $this->signInRole('assistant');

        $this->post(route('devices.store'), Device::factory()->raw(['location_id' => 100]))
            ->assertSessionHasErrors('location_id');
    }

    /** @test */
    public function it_requires_an_existing_organization_id_when_creating_a_device(): void
    {
        $this->signInRole('assistant');

        $this->post(route('devices.store'), Device::factory()->raw(['organization_id' => 100]))
            ->assertSessionHasErrors('organization_id');
    }

    /** @test */
    public function check_for_all_urls_on_device_create(): void
    {
        $this->signInRole('assistant');

        $this->post(route('devices.store'), Device::factory()->raw(['url' => 100]))
            ->assertSessionHasErrors('url');
        $this->post(route('devices.store'), Device::factory()->raw(['room_url' => 100]))
            ->assertSessionHasErrors('room_url');
        $this->post(route('devices.store'), Device::factory()->raw(['camera_url' => 100]))
            ->assertSessionHasErrors('camera_url');
        $this->post(route('devices.store'), Device::factory()->raw(['power_outlet_url' => 100]))
            ->assertSessionHasErrors('power_outlet_url');
    }

    /** @test */
    public function check_for_device_ip_when_creating_a_device(): void
    {
        $this->signInRole('assistant');

        $this->post(route('devices.store'), Device::factory()->raw(['ip_address' => 100]))
            ->assertSessionHasErrors('ip_address');
    }

    /** @test */
    public function an_assistant_can_create_a_new_device(): void
    {
        Device::factory()->create();

        $this->signInRole('assistant');

        $attributes = [
            'name'                 => 'test device',
            'location_id'          => 1,
            'organization_id'      => 1,
            'opencast_device_name' => 'test device',
            'url'                  => 'https://www.test.com',
            'room_url'             => 'https://www.test.com',
            'camera_url'           => 'https://www.test.com',
            'power_outlet_url'     => 'https://www.test.com',
            'ip_address'           => '192.168.178.1',
            'has_recording_func'   => true,
            'has_livestream_func'  => true,
            'is_hybrid'            => true,
            'operational'          => true,
            'description'          => 'test description',
            'comment'              => 'test description',
            'telephone_number'     => '',

        ];
        $this->post(route('devices.store'), $attributes);

        $this->assertDatabaseHas('devices', ['name' => 'test device']);
    }

    /** @test */
    public function an_assistant_can_view_edit_device_page(): void
    {
        $this->signInRole('assistant');

        $this->get(route('devices.edit', Device::factory()->create()))->assertOk();
    }

    /** @test */
    public function an_admin_is_allowed_to_edit_a_device(): void
    {
        $this->signInRole('admin');

        $this->get(route('devices.edit', $device = Device::factory()->create()))
            ->assertOk()
            ->assertViewIs('backend.devices.edit')
            ->assertViewhas('device', $device);
    }

    /** @test */
    public function an_assistant_can_update_a_device(): void
    {
        $this->signInRole('assistant');

        $device = Device::factory()->create();
        $attributes = [
            'name'                 => 'test device',
            'opencast_device_name' => 'opencast test device',
        ];
        $this->put(route('devices.update', $device), $attributes)
            ->assertRedirect(route('devices.edit', $device));

        $this->assertDatabaseHas('devices', $attributes);
    }

    /** @test */
    public function an_admin_can_update_a_device(): void
    {
        $this->signInRole('admin');

        $device = Device::factory()->create();

        $attributes = [
            'name'                 => '',
            'opencast_device_name' => 'test device'
        ];

        $this->put(route('devices.update', $device), $attributes)->assertSessionHasErrors('name');

        $attributes['name'] = 'test device';

        $this->put(route('devices.update', $device), $attributes)
            ->assertRedirect(route('devices.edit', $device));

        $this->assertDatabaseHas('devices', $attributes);
    }

    /** @test */
    public function an_assistant_can_delete_a_device(): void
    {
        $this->signInRole('assistant');

        $device = Device::factory()->create();

        $this->delete(route('devices.destroy', $device))->assertRedirect(route('devices.index'));

        $this->assertDatabaseMissing('devices', $device->toArray());
    }

    /** @test */
    public function an_admin_can_delete_a_device(): void
    {
        $this->signInRole('admin');

        $device = Device::factory()->create();

        $this->delete(route('devices.destroy', $device))->assertRedirect(route('devices.index'));

        $this->assertDatabaseMissing('devices', $device->toArray());
    }
}
