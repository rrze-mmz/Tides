<?php

use Facades\Tests\Setup\ClipFactory;
use Facades\Tests\Setup\FileFactory;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses()->group('frontend');

test('a visitor cannot upload a video file', function () {
    $clip = ClipFactory::create();

    post(route('admin.clips.asset.transferSingle', $clip), ['asset' => FileFactory::videoFile()])
        ->assertRedirect('login');
});

test('a visitor cannot view dropzone files for a clip', function () {
    $clip = ClipFactory::create();

    get(route('admin.clips.dropzone.listFiles', $clip))->assertRedirect('login');
});
