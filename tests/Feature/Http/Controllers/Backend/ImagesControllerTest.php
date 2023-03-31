<?php

use App\Http\Livewire\ImagesDataTable;
use App\Models\Image;
use App\Models\Presenter;
use Facades\Tests\Setup\FileFactory;
use Illuminate\Support\Facades\Storage;
use function Pest\Laravel\get;
use function Pest\Laravel\patch;

uses()->group('backend');

beforeEach(function () {
    $disk = Storage::fake('images');
    $disk->putFileAs('', FileFactory::imageFile(), 'avatar.png');

    $this->image = Image::factory()->create([
        'description' => 'Image of an avatar',
        'file_name' => 'avatar.png',
        'file_path' => '/data/Thumbnails/avatar.jpg.png',
        'thumbnail_path' => 'avatar.png',
        'mime_type' => null,
        'file_size' => $disk->size('avatar.png'),
        'created_at' => '2023-03-02T12:20:01.000000Z',
        'updated_at' => '2023-03-02T12:20:01.000000Z',
    ]);
});

it('denies access to images index page for guests', function () {
    get(route('images.index'))->assertRedirectToRoute('login');
});

it('denies access to images index page for logged in users', function () {
    signInRole('user');

    get(route('images.index'))->assertForbidden();
});

it('allows access to images index page for moderators', function () {
    signInRole('moderator');

    get(route('images.index'))->assertOk();
});

it('doesn\'t show an create button for moderators', function () {
    signInRole('moderator');

    get(route('images.index'))->assertDontSee(route('images.create'));
});

it('doesn\'t show an edit or delete image button for moderators', function () {
    Image::factory(10)->create();
    signInRole('moderator');

    get(route('images.index'))
        ->assertDontSee('/images/1/edit')
        ->assertDontSee(__('common.actions.delete'));
});

it('it allows access to images index for assistants, admins and superadmins', function () {
    signInRole('assistant');
    get(route('images.index'))->assertOk();
    auth()->logout();

    signInRole('admin');
    get(route('images.index'))->assertOk();
    auth()->logout();

    signInRole('superadmin');
    get(route('images.index'))->assertOk();
    auth()->logout();
});

it('has a button to create new images and a text for info if database has no images', function () {
    signInRole('admin');

    Image::find(1)->delete();

    get(route('images.index'))
        ->assertViewIs('backend.images.index')
        ->assertSeeLivewire(ImagesDataTable::class)
        ->assertSee('No images found. Please create one');
});

it('shows a list of all images', function () {
    Image::factory(10)->create();
    signInRole('moderator');

    get(route('images.index'))
        ->assertSee(Image::all()->first()->file_name);
});

it('has edit and delete links for every image', function (Image $image) {
    signInRole('admin');

    get(route('images.index'))
        ->assertSee(route('images.edit', $image))
        ->assertSee(route('images.destroy', $image));
})->with([fn () => Image::factory()->create()]);

it('denies access to image create form for portal users', function () {
    signInRole('user');

    get(route('images.create'))->assertForbidden();
});

it('denies access to image create form for portal moderators', function () {
    signInRole('moderator');

    get(route('images.create'))->assertForbidden();
});

it('paginates the results', function () {
    Image::factory(50)->create();
    signInRole('moderator');

    get(route('images.index'))->assertDontSee(route('images.edit', Image::all()->last()));
});

it('denies access to image create form for portal assistants', function () {
    signInRole('assistant');

    get(route('images.create'))->assertForbidden();
});

it('allows access to image create for a minimum role of portal admin', function () {
    signInRole('admin');

    get(route('images.create'))
        ->assertOk()
        ->assertViewIs('backend.images.create');
});

it('hasa show page for an image with information about it', function () {
    signInRole('moderator');

    get(route('images.show', $this->image))
        ->assertViewIs('backend.images.show')
        ->assertViewHas(['image', 'mediaInfoContainer'])
        ->assertSee($this->image->file_name);
});

it('lists all presenters using this image', function () {
    signInRole('moderator');
    $presenterA = Presenter::factory()->create();
    $presenterB = Presenter::factory()->create(['image_id' => $this->image->id]);

    get(route('images.show', $this->image))
        ->assertDontSee($presenterA->getFullNameAttribute())
        ->assertSee($presenterB->getFullNameAttribute());
});

it('has an edit page for change image metadata', function () {
    signInRole('admin');

    get(route('images.edit', $this->image))
        ->assertOk()
        ->assertViewIs('backend.images.edit')
        ->assertViewHas(['image', 'mediaInfoContainer'])
        ->assertSee('Edit Image ID')
        ->assertSee('Back to images list');
});

it('lists the last 5 presenters using an image in an image edit page', function () {
    signInRole('admin');

    Presenter::factory(6)->create(['image_id' => $this->image->id]);
    $presenter = Presenter::first();
    $lastPresenter = Presenter::orderByDesc('id')->first();

    get(route('images.edit', $this->image))
        ->assertSee('Used in 6 lecturers')
        ->assertSee(route('presenters.edit', $presenter))
        ->assertSee(route('presenters.edit', $lastPresenter));
});

it('can update an image filename or description', function () {
    signInRole('admin');

    $formData = [
        'description' => 'an updated description',
    ];
    patch(route('images.update', $this->image), $formData)->assertRedirectToRoute('images.edit', $this->image);

    $this->assertDatabaseHas('images', $formData);
});
