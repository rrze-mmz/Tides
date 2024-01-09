<?php

use App\Enums\Acl;
use App\Enums\Role;
use App\Models\Chapter;
use App\Models\Clip;
use App\Models\Image;
use App\Models\Presenter;
use App\Models\Semester;
use App\Models\Tag;
use Carbon\Carbon;
use Facades\Tests\Setup\ClipFactory;
use Facades\Tests\Setup\SeriesFactory;

use function Pest\Faker\fake;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\followingRedirects;
use function Pest\Laravel\get;
use function Pest\Laravel\patch;
use function Pest\Laravel\post;

uses()->group('backend');

it('shows all portal clips in index page for assistants', function () {
    ClipFactory::withAssets(1)->create();
    ClipFactory::withAssets(1)->create();
    ClipFactory::withAssets(1)->create();

    signInRole(Role::MODERATOR);

    get(route('clips.index'))
        ->assertOk()
        ->assertViewIs('backend.clips.index')
        ->assertSee(Clip::all()->first()->title);
});

it('requires a title when creating a new clip', function () {
    signInRole(Role::MODERATOR);

    post(route('clips.store', Clip::factory()->raw(['title' => ''])))->assertSessionHasErrors('title');
});

it('requires a recording date when creating a new clip', function () {
    signInRole(Role::MODERATOR);

    post(route('clips.store', Clip::factory()->raw(['recording_date' => ''])))
        ->assertSessionHasErrors('recording_date');
});

it('requires a semester when creating a new clip', function () {
    signInRole(Role::MODERATOR);

    post(route('clips.store', Clip::factory()->raw(['semester_id' => ''])))->assertSessionHasErrors('semester_id');
});

it('must have a strong password when creating a new clip', function () {
    signInRole(Role::MODERATOR);

    post(route('clips.store', Clip::factory()->raw([
        'title' => 'This is a test',
        'password' => '1234',
    ])))->assertSessionHasErrors('password');

    post(route('clips.store', Clip::factory()->raw([
        'password' => '1234qwER',
    ])))->assertSessionDoesntHaveErrors();
});

it('not allowed for an authenticated user to create a clip', function () {
    signIn();

    post(route('clips.store'), Clip::factory()->raw())->assertForbidden();
});

it('not allowed for a student role to create a clip', function () {
    signInRole(Role::STUDENT);

    post(route('clips.store'), Clip::factory()->raw())->assertForbidden();
});

it('allows a user with role moderator to view create clip form', function () {
    signInRole(Role::MODERATOR);

    get(route('clips.create'))->assertOk()->assertViewIs('backend.clips.create');
});

it('allows a user with role assistant to view create clip form', function () {
    signInRole(Role::ASSISTANT);

    get(route('clips.create'))->assertOk()->assertViewIs('backend.clips.create');
});

it('allows a user with role admin to view create clip form', function () {
    signInRole(Role::ADMIN);

    get(route('clips.create'))->assertOk()->assertViewIs('backend.clips.create');
});

it('allows a moderator to create a clip', function () {
    signInRole(Role::MODERATOR);

    followingRedirects()
        ->post(route('clips.store'), $attributes = Clip::factory()->raw())
        ->assertSee($attributes['title']);
});

it('allows an admin to create a clip', function () {
    signInRole(Role::ADMIN);

    followingRedirects()
        ->post(route('clips.store'), $attributes = Clip::factory()->raw())
        ->assertSee($attributes['title']);
});

it('shows a validation error if presenters array has no integer values', function () {
    signInRole(Role::MODERATOR);

    post(route('clips.store', Clip::factory()->raw(['presenters' => ['1.3', 'test']])))
        ->assertSessionHasErrors('presenters.*');
});

it('shows all available form fields for create a new clip', function () {
    signInRole(Role::MODERATOR);

    get(route('clips.create'))
        ->assertSee('title')
        ->assertSee('description')
        ->assertSee('recording_date')
        ->assertSee('presenters')
        ->assertSee('organization')
        ->assertSee('language')
        ->assertSee('context')
        ->assertSee('format')
        ->assertSee('type')
        ->assertSee('tags')
        ->assertSee('acls')
        ->assertSee('semester')
        ->assertSee('is_public')
        ->assertSee('is_livestream');

    get(route('clips.create'))->assertOk()
        ->assertViewIs('backend.clips.create');
});

it('shows all portal clips in index page for admins', function () {
    ClipFactory::create();
    signInRole(Role::ADMIN);

    get(route('clips.index'))
        ->assertOk()
        ->assertSee(Clip::all()->first()->title);
});

it('it validates a chapter id to assure that belongs to the series when updating a clip', function () {
    $series = SeriesFactory::ownedBy(signInRole(Role::MODERATOR))->withClips(2)->withChapters(1)->create();
    $anotherChapter = Chapter::factory()->create();

    $attributes = [
        'episode' => '1',
        'title' => 'changed',
        'description' => 'changed',
        'recording_date' => now(),
        'chapter_id' => $anotherChapter->id,
        'organization_id' => '1',
        'language_id' => '1',
        'context_id' => '1',
        'format_id' => '1',
        'type_id' => '1',
        'series_id' => $series->id,
        'semester_id' => '1',
    ];

    patch(route('clips.edit', $series->latestClip), $attributes)->assertSessionHasErrors(['chapter_id']);
    assertDatabaseMissing('clips', $attributes);

    $attributes['chapter_id'] = $series->chapters()->first()->id;

    patch(route('clips.edit', $series->latestClip), $attributes)->assertSessionDoesntHaveErrors();
});

it('shows a flash message when a clip is created', function () {
    signInRole(Role::MODERATOR);

    post(route('clips.store'), Clip::factory()->raw())->assertSessionHas('flashMessage');
});

test('a moderator can view the edit clip form and all form fields', function () {
    $clip = ClipFactory::ownedBy($this->signInRole(Role::MODERATOR))->create();

    get(route('clips.edit', $clip))->assertOk();

    get(route('clips.edit', $clip))
        ->assertSee('by '.auth()->user()->getFullNameAttribute())
        ->assertSee('title')
        ->assertSee('description')
        ->assertSee('tags')
        ->assertSee('organization')
        ->assertSee('recording_date')
        ->assertSee('language')
        ->assertSee('context')
        ->assertSee('format')
        ->assertSee('type')
        ->assertSee('tags')
        ->assertSee('presenters')
        ->assertSee('semester')
        ->assertSee('is_public')
        ->assertSee('is_livestream')
        ->assertSee('acls')
        ->assertSee('has_time_availability');
});

it('updates clip supervisor id if logged in user is admin and not the same as the existing supervisor', function () {
    $clip = ClipFactory::ownedBy(signInRole(Role::MODERATOR))->create();

    auth()->logout();
    $admin = signInRole(Role::ADMIN);

    patch(route('clips.edit', $clip), [
        'episode' => '1',
        'title' => 'changed',
        'description' => 'changed',
        'recording_date' => now(),
        'organization_id' => '1',
        'language_id' => '1',
        'context_id' => '1',
        'format_id' => '1',
        'type_id' => '1',
        'is_livestream' => '',
        'semester_id' => '1',
    ]);
    $clip->refresh();

    expect($clip->supervisor_id)->toBe($admin->id);

});

it('denies access to moderators for editing a not owned clip', function () {
    $clip = ClipFactory::create();
    signInRole(Role::MODERATOR);

    get(route('clips.edit', $clip))->assertForbidden();
});

it('allows access to admins for editing a not owned clip', function () {
    $clip = ClipFactory::create();
    signInRole(Role::ADMIN);

    get(route('clips.edit', $clip))->assertOK();
});

test('a superadmin can edit a not owned clip', function () {
    $clip = ClipFactory::create();
    signInRole(Role::SUPERADMIN);

    get(route('clips.edit', $clip))->assertOk();
});

test('a clip with multiple tags can be created', function () {
    signInRole(Role::MODERATOR);
    $attributes = Clip::factory()->raw([
        'tags' => ['php', 'pest', 'phpunit'],
    ]);

    followingRedirects()->post(route('clips.store', $attributes))->assertSee($attributes['tags']);

    $clip = Clip::first();
    assertDatabaseCount('tags', 3);

    expect($clip->tags()->count())->toBe(3);
});

test('a clip with multiple presenters can be created', function () {
    Presenter::factory(2)->create();
    $presenter1 = Presenter::find(1);
    $presenter2 = Presenter::find(2);
    signInRole(Role::MODERATOR);
    post(route('clips.store'), Clip::factory()->raw([
        'presenters' => [$presenter1->id, $presenter2->id],
    ]));
    $clip = Clip::first();

    assertDatabaseCount('presentables', 2);
    expect($clip->presenters()->count())->toBe(2);
});

test('a clip with time availability can be created', function () {
    signInRole(Role::MODERATOR);

    post(route('clips.store'), Clip::factory()->raw([
        'has_time_availability' => 'on',
        'time_availability_start' => Carbon::now(),
    ]));

    assertDatabaseHas('clips', ['id' => 1, 'has_time_availability' => true]);
});

it('displays a create clip form error if time availability end time is earlier than start time', function () {
    signInRole(Role::MODERATOR);
    post(route('clips.store'), Clip::factory()->raw([
        'has_time_availability' => 'on',
        'time_availability_start' => Carbon::now(),
        'time_availability_end' => Carbon::now()->subHour(),
    ]))->assertSessionHasErrors(['time_availability_end']);
});

it('set is_public to false on clip store if time availability is set and the start time is in the future', function () {
    signInRole(Role::MODERATOR);
    post(route('clips.store'), Clip::factory()->raw([
        'is_public' => 'on',
        'has_time_availability' => 'on',
        'time_availability_start' => Carbon::now()->addDay(),
        'time_availability_end' => Carbon::now()->addDays(3),
    ]));
    $clip = Clip::all()->first();
    expect($clip->is_public)->toBe(0);
});

test('a clip with acls can be created', function () {
    signInRole(Role::MODERATOR);
    post(route('clips.store'), Clip::factory()->raw([
        'acls' => [Acl::PASSWORD(), Acl::LMS()],
    ]));

    expect(Clip::first()->acls()->count())->toBe(2);
});

test('clip tags can be removed', function () {
    $clip = ClipFactory::ownedBy(signInRole(Role::MODERATOR))->create();
    $clip->tags()->sync(Tag::factory()->create());
    patch(route('clips.edit', $clip), [
        'episode' => '1',
        'title' => 'changed',
        'description' => 'changed',
        'recording_date' => now(),
        'organization_id' => '1',
        'language_id' => '1',
        'context_id' => '1',
        'format_id' => '1',
        'type_id' => '1',
        'tags' => [],
        'semester_id' => '1',
    ]);

    expect($clip->tags()->count())->toBe(0);
});

test('clip tags can be updated', function () {
    $clip = ClipFactory::ownedBy(signInRole(Role::MODERATOR))->create();
    $tag = Tag::factory()->create();
    $clip->tags()->sync(Tag::factory()->create());
    patch(route('clips.edit', $clip), [
        'episode' => '1',
        'title' => 'changed',
        'description' => 'changed',
        'recording_date' => now(),
        'organization_id' => '1',
        'language_id' => '1',
        'context_id' => '1',
        'format_id' => '1',
        'type_id' => '1',
        'tags' => [$tag->name, 'another tag'],
        'semester_id' => '1',
    ]);

    expect($clip->tags()->count())->toBe(2);
});

test('clip can updated to be a livestream clip', function () {
    $clip = ClipFactory::ownedBy(signInRole(Role::MODERATOR))->create();

    patch(route('clips.edit', $clip), [
        'episode' => '1',
        'title' => 'changed',
        'description' => 'changed',
        'recording_date' => now(),
        'organization_id' => '1',
        'language_id' => '1',
        'context_id' => '1',
        'format_id' => '1',
        'type_id' => '1',
        'is_livestream' => 'on',
        'semester_id' => '1',
    ]);

    $clip->refresh();

    expect($clip->is_livestream)->toBe(1);
});

test('clip can updated to be a clip with time availability', function () {
    $clip = ClipFactory::ownedBy(signInRole(Role::MODERATOR))->create();

    patch(route('clips.edit', $clip), [
        'episode' => '1',
        'title' => 'changed',
        'description' => 'changed',
        'recording_date' => now(),
        'organization_id' => '1',
        'language_id' => '1',
        'context_id' => '1',
        'format_id' => '1',
        'type_id' => '1',
        'is_livestream' => 'on',
        'semester_id' => '1',
        'has_time_availability' => 'on',
        'time_availability_start' => Carbon::now()->addDay(),
        'time_availability_end' => Carbon::now()->addDays(4),
    ]);

    $clip->refresh();

    expect($clip->has_time_availability)->toBe(1);
});

it('displays an update clip form error if time availability end time is earlier than start time', function () {
    $clip = ClipFactory::ownedBy(signInRole(Role::MODERATOR))->create();
    patch(route('clips.update', $clip), [
        'episode' => '1',
        'title' => 'changed',
        'description' => 'changed',
        'recording_date' => now(),
        'organization_id' => '1',
        'language_id' => '1',
        'context_id' => '1',
        'format_id' => '1',
        'type_id' => '1',
        'is_livestream' => 'on',
        'semester_id' => '1',
        'has_time_availability' => 'on',
        'time_availability_start' => Carbon::now()->addDay(),
        'time_availability_end' => Carbon::now()->subDays(4),
    ])->assertSessionHasErrors(['time_availability_end']);
});

it('set is_public to false on clip update if time availability is set and the start time is in the future', function () {
    $clip = ClipFactory::ownedBy(signInRole(Role::MODERATOR))->create();
    patch(route('clips.update', $clip), [
        'episode' => '1',
        'title' => 'changed',
        'description' => 'changed',
        'recording_date' => now(),
        'organization_id' => '1',
        'language_id' => '1',
        'context_id' => '1',
        'format_id' => '1',
        'type_id' => '1',
        'is_livestream' => 'on',
        'semester_id' => '1',
        'has_time_availability' => 'on',
        'time_availability_start' => Carbon::now()->addDay(),
        'time_availability_end' => Carbon::now()->addDays(4),
    ]);
    $clip->refresh();

    expect($clip->is_public)->toBe(0);
});

test('create clip form should remember old values on validation errors', function () {
    signInRole(Role::MODERATOR);

    $attributes = [
        'title' => 'Clip title',
        'description' => fake()->sentence(500),
        'recording_date' => now()->toDateString(),
        'organization_id' => '1',
        'language_id' => '1',
        'context_id' => '1',
        'format_id' => '1',
        'type_id' => '1',
        'semester_id' => '1',
    ];

    post(route('clips.store'), $attributes);

    followingRedirects();

    get(route('clips.create'))->assertSee($attributes);
});

test('a moderator can update his clip', function () {
    $clip = ClipFactory::ownedBy($this->signInRole(Role::MODERATOR))->create();

    get(route('clips.edit', $clip))->assertSee($clip->title);

    patch(route('clips.edit', $clip), $attributes = [
        'episode' => '1',
        'title' => 'changed',
        'description' => 'changed',
        'recording_date' => now(),
        'organization_id' => '1',
        'language_id' => '1',
        'context_id' => '1',
        'format_id' => '1',
        'type_id' => '1',
        'semester_id' => '1',
    ]);

    $clip->refresh();

    assertDatabaseHas('clips', [
        'episode' => '1',
        'title' => 'changed',
        'description' => 'changed',
        'organization_id' => '1',
        'semester_id' => '1',
    ]);

    get(route('clips.edit', $clip))->assertSee($attributes['description']);
});

it('denies access to a moderator when updating a not owned clip', function () {
    $clip = ClipFactory::create();
    signInRole(Role::MODERATOR);
    $attributes = [
        'episode' => '1',
        'title' => 'changed',
        'description' => 'changed',
        'recording_date' => now(),
        'organization_id' => '1',
        'language_id' => '1',
        'context_id' => '1',
        'format_id' => '1',
        'type_id' => '1',
        'semester_id' => '1',
    ];
    patch(route('clips.edit', $clip), $attributes)->assertForbidden();
    $clip->refresh();

    assertDatabaseMissing('clips', $attributes);
});

it('allows updating a not owned clip for admin users', function () {
    $clip = ClipFactory::create();
    signInRole(Role::ADMIN);
    $attributes = [
        'episode' => '1',
        'title' => 'changed',
        'description' => 'changed',
        'recording_date' => now(),
        'organization_id' => '1',
        'language_id' => '1',
        'context_id' => '1',
        'format_id' => '1',
        'type_id' => '1',
        'semester_id' => '1',
    ];
    followingRedirects()->patch(route('clips.edit', $clip), $attributes)->assertOk();
    $clip->refresh();

    assertDatabaseHas('clips', $attributes);
});

it('updates clip slug if title is changed', function () {
    $clip = ClipFactory::ownedBy($this->signInRole(Role::MODERATOR))->create();
    patch(route('clips.edit', $clip), [
        'episode' => '1',
        'title' => 'Title changed',
        'recording_date' => now(),
        'organization_id' => '1',
        'language_id' => '1',
        'context_id' => '1',
        'format_id' => '1',
        'type_id' => '1',
        'semester_id' => Semester::current()->first()->id,
    ]);

    $clip->refresh();

    expect($clip->slug)
        ->toBe($clip->episode.'-title-changed-'.str(Semester::current()->get()->first()->acronym)->lower());

});

it('keeps the same slug if title is not changed', function () {

    signInRole(Role::MODERATOR);

    post(route('clips.store'), [
        'episode' => '1',
        'title' => 'Test clip',
        'recording_date' => now(),
        'description' => 'test',
        'organization_id' => '1',
        'language_id' => '1',
        'context_id' => '1',
        'format_id' => '1',
        'type_id' => '1',
        'image_id' => Image::factory()->create()->id,
        'semester_id' => Semester::current()->first()->id,
    ]);

    $clip = Clip::find(1);
    patch(route('clips.edit', $clip), ['episode' => '2', 'title' => 'Test clip', 'description' => 'test']);
    $clip->refresh();
    $semester = str($clip->semester->acronym)->lower();
    $assertedClipTitle = "{$clip->episode}-test-clip-{$semester}";

    expect($clip->slug)->toBe($assertedClipTitle);

});

it('shows a flash message when a clip is updated', function () {
    $clip = ClipFactory::ownedBy(signInRole(Role::MODERATOR))->create();

    patch(route('clips.edit', $clip), [
        'episode' => '1',
        'title' => 'changed',
        'recording_date' => Carbon::now(),
        'description' => 'changed',
        'semester_id' => 1,
        'language_id' => 1,
        'organization_id' => '1',
        'context_id' => 1,
        'context_id' => '1',
        'format_id' => '1',
        'type_id' => '1',
        'image_id' => Image::factory()->create()->id,
    ])->assertRedirect()->assertSessionHas('flashMessage');
});

test('a moderator cannot delete an not owned clip', function () {
    $clip = ClipFactory::create();
    signInRole(Role::MODERATOR);

    delete(route('clips.edit', $clip))->assertForbidden();
    assertDatabaseHas('clips', $clip->only('id'));
});

test('an admin user can delete a not owned clip', function () {
    $clip = ClipFactory::create();
    signInRole(Role::ADMIN);

    followingRedirects()->delete(route('clips.edit', $clip))->assertOk();
    assertDatabaseMissing('clips', $clip->only('id'));
});

test('a moderator can delete owned clips', function () {
    $clip = ClipFactory::ownedBy(signInRole(Role::MODERATOR))->create();

    delete(route('clips.destroy', $clip))->assertRedirect(route('clips.index'));
    assertDatabaseMissing('clips', $clip->only('id'));
});
