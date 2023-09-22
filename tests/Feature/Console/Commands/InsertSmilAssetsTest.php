<?php

use App\Enums\Content;
use Facades\Tests\Setup\ClipFactory;
use Illuminate\Support\Facades\Storage;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('shows a message if smil is created', function () {
    $clip = ClipFactory::withAssets(4)->create();

    $this->artisan('smil:insert')->expectsOutput('Finish clip ID '.$clip->id);
});

it('generates a smil file and inserts it to database', function () {
    Storage::fake('videos');

    $clip = ClipFactory::withAssets(4)->create();

    expect($clip->assets()->count())->toEqual(4);

    $this->artisan('smil:insert');

    $smil = $clip->getAssetsByType(Content::SMIL)->first();

    $this->assertDatabaseHas('assets', ['id' => $smil->id]);

    Storage::disk('videos')->assertExists($smil->path.$smil->original_file_name);
});
