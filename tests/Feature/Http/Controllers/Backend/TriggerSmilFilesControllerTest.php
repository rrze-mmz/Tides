<?php

use App\Enums\Role;
use App\Services\WowzaService;
use Facades\Tests\Setup\ClipFactory;
use Tests\Setup\WorksWithWowzaClient;

use function Pest\Laravel\get;

uses()->group('backend');
uses(WorksWithWowzaClient::class);

beforeEach(function () {
    $this->moderator = $this->signInRole(Role::MODERATOR);
    $this->clip = ClipFactory::withAssets(2)->ownedBy($this->moderator)->create();
    $this->mockHandler = $this->swapWowzaClient();
    $this->wowzaService = app(WowzaService::class);
});

it('is forbidden for a logged in user to trigger smil files for a clip', function () {
    auth()->logout();
    $this->signInRole(Role::STUDENT);

    get(route('admin.clips.triggerSmilFiles', $this->clip))->assertForbidden();
});

it('is forbidden for a moderator to trigger smil files for a clip', function () {
    get(route('admin.clips.triggerSmilFiles', $this->clip))->assertForbidden();
});

it('displays a flash message that the smil files are created for a clip', function () {
    $this->signInRole(Role::ADMIN);

    get(route('admin.clips.triggerSmilFiles', $this->clip))->assertSessionHas(
        'flashMessage',
        "{$this->clip->title} smil files created successfully",
    );
});
