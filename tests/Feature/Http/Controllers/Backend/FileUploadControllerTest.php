<?php

use App\Enums\Role;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use function Pest\Laravel\freezeTime;
use function Pest\Laravel\post;

beforeEach(function () {
    freezeTime();
    Str::createRandomStringsUsing(static fn (): string => 'random-string');
});

it('redirect not logged in users to login page', function () {
    post(route('uploads.process'), [
        'image' => UploadedFile::fake()->image('avatar.png'),
    ])->assertRedirectToRoute('login');
});

test('file can be temporarily uploaded to tmp for a single file upload', function () {
    $file = UploadedFile::fake()->image('avatar.png');
    $expectedFilePath = 'tmp/'.now()->timestamp.'-random-string';

    signInRole(Role::ADMIN);
    post(route('uploads.process'), [
        'image' => $file,
    ])->assertOk()->assertSee($expectedFilePath);

    Storage::assertExists($expectedFilePath);
});

it('returns an error if no file is provided', function () {
    signInRole(Role::ADMIN);
    post(route('uploads.process'))->assertStatus(422);
});
