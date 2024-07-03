<?php

use App\Enums\Role;
use App\Models\Asset;
use Facades\Tests\Setup\ClipFactory;
use Facades\Tests\Setup\FileFactory;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\post;

uses()->beforeEach(function () {
    Storage::fake('videos');
    Storage::fake('thumbnails');
});

test('a moderator cannot delete an asset they do not own', function () {
    $asset = Asset::factory()->create();
    $clip = ClipFactory::create();
    $clip->addAsset($asset);
    signInRole(Role::MODERATOR);
    delete(route('assets.destroy', $asset))->assertForbidden();
});

test('a moderator can delete an owned clip asset', function () {
    $clip = ClipFactory::withAssets(1)->ownedBy(signInRole(Role::MODERATOR))->create();
    expect($clip->assets->count())->toBe(2); // it will create also the smil file for the video

    delete(route('assets.destroy', $clip->assets()->first()))->assertRedirect(route('clips.edit', $clip));
    expect($clip->assets()->count())->toBe(1);
});

test('deleting an asset should also delete the file from storage', function () {
    $clip = ClipFactory::ownedBy(signInRole(Role::MODERATOR))->create();
    post(route('admin.clips.asset.transferSingle', $clip), ['asset' => FileFactory::videoFile()]);
    $asset = $clip->assets()->first();

    assertDatabaseHas('assets', ['path' => $asset->path]);
    Storage::disk('videos')->assertExists($asset->path);

    $asset->delete();
    assertModelMissing($asset);
    Storage::disk('videos')->assertMissing($asset->path);
});

test('an admin can delete a not owned clip asset', function () {
    $asset = Asset::factory()->create();
    $clip = ClipFactory::create();
    $clip->addAsset($asset);
    signInRole(Role::ADMIN);
    delete(route('assets.destroy', $asset));

    assertModelMissing($asset);
});

test('deleting an asset should also delete a clip poster', function () {
    $clip = ClipFactory::ownedBy(signInRole(Role::MODERATOR))->create();
    post(route('admin.clips.asset.transferSingle', $clip), ['asset' => FileFactory::videoFile()]);
    $clip->refresh();
    $image = $clip->posterImage;

    delete(route('assets.destroy', $clip->assets()->first()));
    Storage::disk('thumbnails')->assertMissing($image);
});

test('deleting an asset should update clip poster image column', function () {
    $clip = ClipFactory::ownedBy(signInRole(Role::MODERATOR))->create();
    post(route('admin.clips.asset.transferSingle', $clip), ['asset' => FileFactory::videoFile()]);
    delete(route('assets.destroy', $clip->assets()->first()));
    $clip->refresh();

    expect($clip->posterImage)->toBeNull();
});
