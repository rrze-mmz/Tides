<?php

use App\Enums\Role;
use App\Models\Clip;
use Facades\Tests\Setup\ClipFactory;

use function Pest\Laravel\get;

uses()->group('backend');

beforeEach(function () {

    $this->clip = ClipFactory::withAssets(3)->ownedBy(signInRole(Role::MODERATOR))->create();
});

it('hides preview image actions for a clip without assets', function () {
    $clipWithoutAssets = Clip::factory()->create(['owner_id' => auth()->user()->id]);

    get(route('clips.edit', $clipWithoutAssets))->assertDontSee('Preview Image')
        ->assertDontSee(route('clips.generatePreviewImageFromFrame', $clipWithoutAssets));
});

it('displays preview image actions for a clip with assets', function () {
    get(route('clips.edit', $this->clip))->assertSee('Preview image')
        ->assertSee(route('clips.generatePreviewImageFromFrame', $this->clip));
});
