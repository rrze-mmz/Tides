<?php

use App\Enums\Role;
use App\Http\Livewire\DevicesDataTable;
use App\Models\Device;
use App\Models\DeviceLocation;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

uses()->group('backend');

it('denies access to simple user to manage devices', function () {
    get(route('devices.index'))->assertRedirect(route('login'));
    signInRole(Role::USER);
    get(route('devices.index'))->assertForbidden();
});

it('denies access to moderator to manage devices', function () {
    signInRole(Role::MODERATOR);
    $device = Device::factory()->create();

    get(route('devices.index'))->assertForbidden();
    get(route('devices.create'))->assertForbidden();
    get(route('devices.edit', $device))->assertForbidden();
    post(route('devices.store'), ['name' => 'test'])->assertForbidden();
    get(route('devices.edit', $device))->assertForbidden();
    put(route('devices.update', $device), ['name' => 'test'])->assertForbidden();
    delete(route('devices.destroy', $device))->assertForbidden();
});

it('allows to a portal assistant to manage devices', function () {
    signInRole(Role::ASSISTANT);
    get(route('devices.index'))->assertOk();
});

it('allows to a portal admin to manage devices', function () {
    signInRole(Role::ADMIN);

    get(route('devices.index'))
        ->assertOk()
        ->assertViewHas('devices')
        ->assertViewIs('backend.devices.index');
});

it('shows a devices datatable component on index page', function () {
    signInRole(Role::ASSISTANT);
    get(route('devices.index'))->assertSeeLivewire('devices-data-table');
});

it('shows a create device button on index page', function () {
    signInRole(Role::ASSISTANT);
    get(route('devices.index'))->assertSee('Create a new device');
});

it('shows an edit device button on index page', function () {
    signInRole(Role::ASSISTANT);
    Device::factory()->create(['name' => 'Exon DMP 351']);

    get(route('devices.index'))->assertSee(__('common.actions.edit'));
});

it('shows a delete device button on index page', function () {
    signInRole(Role::ASSISTANT);

    Device::factory()->create(['name' => 'Exon DMP 351']);

    get(route('devices.index'))->assertSee(__('common.actions.delete'));
});

it('can search for device name in index data table', function () {
    $recDevice1 = Device::factory()->create(['name' => 'Exon DMP 351']);
    $recDevice2 = Device::factory()->create(['name' => 'Wolf 300']);

    Livewire::test(DevicesDataTable::class)
        ->set('search', 'Exon')
        ->assertSee($recDevice1->name)
        ->assertDontSee($recDevice2->name);
});

it('can search for device location in index data table', function () {
    $medBuilding = DeviceLocation::factory()->create(['name' => 'Medical Center']);
    $computerScienceBuilding = DeviceLocation::factory()->create(['name' => 'Computer Science Building']);

    $recDevice1 = Device::factory()->create([
        'name' => 'Exon DMP 351',
        'location_id' => $medBuilding->id,
    ]);
    $recDevice2 = Device::factory()->create([
        'name' => 'Wolf 300',
        'location_id' => $computerScienceBuilding->id,
    ]);

    Livewire::test(DevicesDataTable::class)
        ->set('search', 'medical')
        ->assertSee($recDevice1->name)
        ->assertDontSee($recDevice2->name);
});

it('allows displaying create new device form to portal assistant', function () {
    signInRole(Role::ASSISTANT);

    get(route('devices.create'))
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
});

it('requires a name when creating a new device', function () {
    signInRole(Role::ASSISTANT);
    post(route('devices.store'), Device::factory()->raw(['name' => '']))
        ->assertSessionHasErrors('name');
});

it('requires an existing location id when creating a new device', function () {
    signInRole(Role::ASSISTANT);
    post(route('devices.store'), Device::factory()->raw(['location_id' => 100]))
        ->assertSessionHasErrors('location_id');
});

it('requires an existing organization id when creating a new device', function () {
    signInRole(Role::ASSISTANT);
    post(route('devices.store'), Device::factory()->raw(['organization_id' => 100]))
        ->assertSessionHasErrors('organization_id');
});

it('check for all urls on device create', function () {
    signInRole(Role::ASSISTANT);

    post(route('devices.store'), Device::factory()->raw(['url' => 100]))->assertSessionHasErrors('url');
    post(route('devices.store'), Device::factory()->raw(['room_url' => 100]))->assertSessionHasErrors('room_url');
    post(route('devices.store'), Device::factory()->raw(['camera_url' => 100]))->assertSessionHasErrors('camera_url');
    post(route('devices.store'), Device::factory()->raw(['power_outlet_url' => 100]))
        ->assertSessionHasErrors('power_outlet_url');
});

it('checks for device ip when creating a new device', function () {
    signInRole(Role::ASSISTANT);
    post(route('devices.store'), Device::factory()->raw(['ip_address' => 100]))
        ->assertSessionHasErrors('ip_address');
});

it('allows access to create a new device form to portal assistants', function () {
    Device::factory()->create();
    signInRole(Role::ASSISTANT);

    $attributes = [
        'name' => 'test device',
        'location_id' => 1,
        'organization_id' => 1,
        'opencast_device_name' => 'test device',
        'url' => 'https://www.test.com',
        'room_url' => 'https://www.test.com',
        'camera_url' => 'https://www.test.com',
        'power_outlet_url' => 'https://www.test.com',
        'ip_address' => '192.168.178.1',
        'has_recording_func' => true,
        'has_livestream_func' => true,
        'is_hybrid' => true,
        'operational' => true,
        'description' => 'test description',
        'comment' => 'test description',
        'telephone_number' => '',

    ];
    post(route('devices.store'), $attributes);

    assertDatabaseHas('devices', ['name' => 'test device']);
});

it('allows access to device edit form for portal assistant', function () {
    signInRole(Role::ASSISTANT);
    get(route('devices.edit', Device::factory()->create()))->assertOk();
});

it('allows access to device edit form for portal admin', function () {
    signInRole(Role::ADMIN);
    get(route('devices.edit', $device = Device::factory()->create()))
        ->assertOk()
        ->assertViewIs('backend.devices.edit')
        ->assertViewhas('device', $device);
});

it('allows portal assistants to update a device', function () {
    signInRole(Role::ASSISTANT);
    $device = Device::factory()->create();
    $attributes = [
        'name' => 'test device',
        'opencast_device_name' => 'opencast test device',
    ];
    put(route('devices.update', $device), $attributes)
        ->assertRedirect(route('devices.edit', $device));

    assertDatabaseHas('devices', $attributes);

});

it('allows to portal admins to update a device', function () {
    signInRole(Role::ADMIN);

    $device = Device::factory()->create();

    $attributes = [
        'name' => '',
        'opencast_device_name' => 'test device',
    ];

    put(route('devices.update', $device), $attributes)->assertSessionHasErrors('name');

    $attributes = [
        'name' => 'test device',
        'description' => 'This is a test device',
        'comment' => 'This is a comment for the test device',
    ];

    put(route('devices.update', $device), $attributes)
        ->assertRedirect(route('devices.edit', $device));

    assertDatabaseHas('devices', $attributes);
});

it('allows to portal assistant to delete a device', function () {
    signInRole(Role::ASSISTANT);
    $device = Device::factory()->create();

    delete(route('devices.destroy', $device))->assertRedirect(route('devices.index'));
    assertDatabaseMissing('devices', $device->toArray());
});

it('allows to portal admin to delete a device', function () {
    signInRole(Role::ADMIN);
    $device = Device::factory()->create();

    delete(route('devices.destroy', $device))->assertRedirect(route('devices.index'));
    assertDatabaseMissing('devices', $device->toArray());
});
