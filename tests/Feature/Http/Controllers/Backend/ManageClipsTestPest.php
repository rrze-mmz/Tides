<?php

use App\Enums\Acl;
use App\Enums\Role;
use App\Models\Chapter;
use App\Models\Clip;
use App\Models\Presenter;
use App\Models\Tag;
use Facades\Tests\Setup\ClipFactory;
use Facades\Tests\Setup\SeriesFactory;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\followingRedirects;
use function Pest\Laravel\get;
use function Pest\Laravel\patch;
use function Pest\Laravel\post;

uses()->group('backend');

it('shows a create clip button in clips index if moderator has no clips', function () {
    signInRole(Role::MODERATOR);

    get(route('clips.index'))->assertSee('Create new clip');
});

it('loads trix editor for clip description', function () {
    signInRole(Role::MODERATOR);

    get(route('clips.create'))->assertSee('trix-editor');
    get(route('clips.edit', ClipFactory::ownedBy(auth()->user())->create()))->assertSee('trix-editor');
});

it('shows all portal clips in index page for assistants', function () {
    Clip::factory(10)->create();

    signInRole(Role::ASSISTANT);

    get(route('clips.index'))
        ->assertOk()
        ->assertViewIs('backend.clips.index')->assertViewHas('clips')
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
        ->assertSee('is_public');

    get(route('clips.create'))->assertOk()
        ->assertViewIs('backend.clips.create');
});
it('shows all portal clips in index page for admins', function () {
    Clip::factory(10)->create();

    signInRole(Role::ADMIN);

    get(route('clips.index'))
        ->assertOk()
        ->assertViewIs('backend.clips.index')->assertViewHas('clips')
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

    $attributes['chapter_id'] = $series->chapters()->first();

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
        ->assertSee('acls');
});

it('shows series information if a clip belongs to a certain series', function () {
    $series = SeriesFactory::ownedBy(signInRole(Role::MODERATOR))->withclips(2)->create();

    get(route('clips.edit', $series->clips()->first()))->assertSee($series->title);
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

it('hides owner if a clip does not have one', function () {
    $clip = ClipFactory::create();
    $clip->owner_id = null;
    $clip->save();
    signInRole(Role::ADMIN);

    get(route('clips.edit', $clip))->assertOk()->assertDontSee('created by');
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
