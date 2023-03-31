<?php

use App\Enums\Content;
use App\Models\Asset;
use App\Models\Clip;
use App\Models\Semester;
use App\Models\Series;
use App\Models\User;
use Facades\Tests\Setup\FileFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use function Pest\Laravel\get;

uses()->group('unit');

beforeEach(function () {
    $this->clip = Clip::factory()->create();
    Storage::fake('thumbnails');
});

it('has a path', function () {
    expect($this->clip->path())->toBe('/clips/'.$this->clip->slug);
});

it('has an admin path', function () {
    expect($this->clip->adminPath())->toBe('/admin/clips/'.$this->clip->slug);
});

it('has a slug route', function () {
    expect($this->clip->path())->toBe('/clips/'.Str::slug(
        $this->clip->episode.'-'.$this->clip->title.'-'.Semester::current()->get()->first()->acronym
    ));
});

it('has an inceremental slug', function () {
    $anotherClip = Clip::factory()->create(['title' => $this->clip->title, 'episode' => $this->clip->episode]);

    expect($anotherClip->slug)->toBe($this->clip->slug.'-2');
});

it('has a unique slug', function () {
    $clipA = Clip::factory()->create(['title' => 'A test title', 'slug' => 'A test title']);
    $clipB = Clip::factory()->create(['title' => 'A test title', 'slug' => 'A test title']);

    expect($clipB->slug)->not()->toBe($clipA->slug);
});

it('belongs to a series', function () {
    $this->assertInstanceOf(BelongsTo::class, $this->clip->series());
});

it('has many assets', function () {
    Asset::factory(2)->create(['clip_id' => $this->clip->id]);

    expect($this->clip->assets()->count())->toBe(2);
});

it('has many collections', function () {
    $this->assertInstanceOf(BelongsToMany::class, $this->clip->collections());
});

it('has only one semester', function () {
    $this->assertInstanceOf(BelongsTo::class, $this->clip->semester());
});

it('belongs to an organization unit', function () {
    $this->assertInstanceOf(BelongsTo::class, $this->clip->organisation());
});

it('belongs to an image', function () {
    $this->assertInstanceOf(BelongsTo::class, $this->clip->image());
});

it('has one language', function () {
    $this->assertInstanceOf(BelongsTo::class, $this->clip->language());
});

it('has one context', function () {
    $this->assertInstanceOf(HasOne::class, $this->clip->context());
});

it('has one format', function () {
    $this->assertInstanceOf(HasOne::class, $this->clip->format());
});

it('has one type', function () {
    $this->assertInstanceOf(HasOne::class, $this->clip->type());
});

it('belongs to an owner', function () {
    $this->assertInstanceOf(User::class, $this->clip->owner);
});

it('has many presenters using presentable trait', function () {
    $this->assertInstanceOf(MorphToMany::class, $this->clip->presenters());
});

it('has many documents using documentable trait', function () {
    $this->assertInstanceOf(MorphToMany::class, $this->clip->documents());
});

it('has many comments', function () {
    $this->assertInstanceOf(MorphMany::class, $this->clip->comments());
});

it('can add an asset', function () {
    $asset = $this->clip->addAsset([
        'disk' => 'videos',
        'original_file_name' => 'video.mp4',
        'path' => '/videos/',
        'duration' => '100',
        'guid' => Str::uuid(),
        'width' => '1920',
        'height' => '1080',
        'type' => 'video',
    ]);

    $this->assertInstanceOf(Asset::class, $asset);
});

it('can update it\'s poster image', function () {
    expect($this->clip->posterImage)->toBeNull();

    $file = FileFactory::videoFile();

    $file->storeAs('thumbnails', $this->clip->id.'_poster.png');

    $this->clip->updatePosterImage();

    Storage::assertExists('/thumbnails/'.$this->clip->posterImage);
});

it('can add tags', function () {
    $this->clip->addTags(collect(['php', 'tides']));

    expect($this->clip->tags()->count())->toBe(2);
});

it('can fetch previous and nect clip models if clip belongs to a series', function () {
    $series = Series::factory()->create();

    Clip::factory()->create([
        'title' => 'first clip',
        'series_id' => $series->id,
        'episode' => 1,
    ]);

    $secondClip = Clip::factory()->create([
        'title' => 'second clip',
        'series_id' => $series->id,
        'episode' => 2,
    ]);

    Clip::factory()->create([
        'title' => 'third clip',
        'series_id' => $series->id,
        'episode' => 3,
    ]);

    $this->assertInstanceOf(Collection::class, $secondClip->previousNextClipCollection());
    $this->assertInstanceOf(Clip::class, $secondClip->previousNextClipCollection()->get('previousClip'));
    $this->assertInstanceOf(Clip::class, $secondClip->previousNextClipCollection()->get('nextClip'));
});

it('has a public scope', function () {
    $this->assertInstanceOf(Builder::class, Clip::public());
});

it('has a single clip scope', function () {
    $this->assertInstanceOf(Builder::class, Clip::single());
});

test('clip owner can be null', function () {
    $user = User::factory()->create();

    $clip = $user->clips()->create(['title' => 'test', 'slug' => 'test', 'semester_id' => 1]);

    $user->delete();

    $clip = Clip::find($clip->id);

    $this->assertNull($clip->owner_id);
});

it('resolves also an id in route', function () {
    get('clips/'.$this->clip->id)->assertForbidden();
    get(route('frontend.clips.show', $this->clip->id))->assertStatus(403);
    get('clips/291')->assertStatus(404);
});

it('fetches assets by type', function () {
    $this->assertInstanceOf(HasMany::class, $this->clip->getAssetsByType(Content::PRESENTER));
});

it('creates a folder id after model save', function () {
    //second db update will be done in clip observer class
    $this->assertEquals('TIDES_ClipID_1', $this->clip->folder_id);
});

it('has a method for returning caption assets', function () {
    $this->assertNull($this->clip->getCaptionAsset());
});
