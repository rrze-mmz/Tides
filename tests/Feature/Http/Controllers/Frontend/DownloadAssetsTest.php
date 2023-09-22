<?php

use App\Enums\Role;
use Facades\Tests\Setup\ClipFactory;
use Facades\Tests\Setup\FileFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

use function Pest\Laravel\post;

uses(WithFaker::class);
uses()->group('frontend');

it('downloads an asset', function () {
    Storage::fake('videos');
    $clip = ClipFactory::ownedBy(signInRole(Role::MODERATOR))->create();
    post(route('admin.clips.asset.transferSingle', $clip), ['asset' => FileFactory::videoFile()]);
    $asset = $clip->assets()->first();

    expect(response()->download(Storage::disk('videos')
        ->path($asset->path)))->toBeInstanceOf(BinaryFileResponse::class);
    Storage::disk('videos')->delete($asset->path);
});
