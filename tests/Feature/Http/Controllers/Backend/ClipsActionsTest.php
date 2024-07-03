<?php

use App\Enums\Acl;
use App\Enums\Content;
use App\Enums\Role;
use App\Livewire\CommentsSection;
use App\Models\Asset;
use App\Models\Clip;
use App\Services\OpencastService;
use Facades\Tests\Setup\ClipFactory;
use Facades\Tests\Setup\FileFactory;
use Facades\Tests\Setup\SeriesFactory;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\Setup\WorksWithOpencastClient;

use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\patch;
use function Pest\Laravel\post;

uses()->group('backend');
uses(WorksWithOpencastClient::class);

it('shows series information if a clip belongs to a certain series', function () {
    $series = SeriesFactory::ownedBy(signInRole(Role::MODERATOR))->withclips(2)->create();

    get(route('clips.edit', $series->clips()->first()))->assertSee($series->title);
});

it('hides all asset options if a clip is tagged as a livestream clip', function () {
    signInRole(Role::MODERATOR);
    $clip = ClipFactory::create(['owner_id' => auth()->user(), 'is_livestream' => true]);

    get(route('clips.edit', $clip))
        ->assertOk()
        ->assertDontSee(route('admin.clips.asset.transferSingle', $clip))
        ->assertDontSee(route('admin.clips.dropzone.listFiles', $clip));
});

it('hides owner if a clip does not have one', function () {
    $clip = ClipFactory::create();
    $clip->owner_id = null;
    $clip->save();
    signInRole(Role::ADMIN);

    get(route('clips.edit', $clip))->assertOk()->assertDontSee('created by');
});

it('shows a create clip button in clips index if moderator has no clips', function () {
    signInRole(Role::MODERATOR);

    get(route('clips.index'))->assertSee('Create new clip');
});

it('loads trix editor for clip description', function () {
    signInRole(Role::MODERATOR);

    get(route('clips.create'))->assertSee('trix-editor');
    get(route('clips.edit', ClipFactory::ownedBy(auth()->user())->create()))->assertSee('trix-editor');
});

it('has an upload button in clip edit form', function () {
    $clip = ClipFactory::ownedBy(signInRole(Role::MODERATOR))->create();

    get(route('clips.edit', $clip))->assertSee('Upload');
});

it('shows an lms test link if clip has an lms acl and user has an admin role ', function () {

    $userClip = ClipFactory::ownedBy(signInRole(Role::MODERATOR))->create();
    get(route('clips.edit', $userClip))->assertDontSee('LMS Test Link');

    $adminClip = ClipFactory::ownedBy(signInRole(Role::ADMIN))->create();
    get(route('clips.edit', $adminClip))->assertDontSee('LMS Test Link');

    $adminClip->addAcls(collect([Acl::LMS()]));
    get(route('clips.edit', $adminClip))->assertSee('LMS Test Link');
});

it('has opencast action buttons if opencast server exists', function () {
    $mockHandler = $this->swapOpencastClient();
    app(OpencastService::class);
    $mockHandler->append($this->mockHealthResponse());

    get(route('clips.edit', ClipFactory::ownedBy(signInRole(Role::MODERATOR))->create()))
        ->assertSee('Ingest to Opencast')
        ->assertSee('Transfer files from Opencast');
});

it('hides opencast action buttons if opencast server does not exists', function () {
    $mockHandler = $this->swapOpencastClient();
    app(OpencastService::class);
    $mockHandler->append(new Response());

    get(route('clips.edit', ClipFactory::ownedBy(signInRole(Role::MODERATOR))->create()))
        ->assertDontSee('Ingest to Opencast')
        ->assertDontSee('Transfer files from Opencast');
});

it('shows a flash message when a clip is deleted', function () {
    $clip = ClipFactory::ownedBy(signInRole(Role::MODERATOR))->create();
    delete(route('clips.edit', $clip))->assertSessionHas('flashMessage');
});

it('can toggle comments', function () {
    $clip = ClipFactory::ownedBy(signInRole(Role::MODERATOR))->create();
    get($clip->path())->assertDontSee('Comments');
    patch(route('clips.update', $clip), [
        'title' => $clip->title,
        'episode' => $clip->episode,
        'recording_date' => now(),
        'organization_id' => '1',
        'language_id' => '1',
        'context_id' => '1',
        'format_id' => '1',
        'type_id' => '1',
        'semester_id' => '1',
        'allow_comments' => 'on',
    ]);
    $clip->refresh();
    get(route('frontend.clips.show', $clip))->assertOk()->assertSee('Comments');
});

it('displays previous and next clip id links', function () {
    signInRole(Role::ADMIN);
    SeriesFactory::withClips(3)->create();
    $clip = Clip::find(2);
    $previousClip = Clip::find(1);
    $nextClip = Clip::find(3);

    get(route('clips.edit', $clip))
        ->assertSee('Previous')
        ->assertSee('Next')
        ->assertSee($previousClip->adminPath())
        ->assertSee($nextClip->adminPath());
});

it('hides previous clip id links if clip is the first on a series', function () {
    signInRole(Role::ADMIN);
    SeriesFactory::withClips(3)->create();
    $clip = Clip::find(1);
    $nextClip = Clip::find(2);
    get(route('clips.edit', $clip))
        ->assertDontSee('Previous')
        ->assertSee('Next')
        ->assertSee($nextClip->adminPath());
});

it('hides next clip id if clip is the last on a series', function () {
    signInRole(Role::ADMIN);
    SeriesFactory::withClips(4)->create();
    $clip = Clip::find(4);
    $previousClip = Clip::find(3);

    get(route('clips.edit', $clip))
        ->assertSee('Previous')
        ->assertDontSee('Next')
        ->assertSee($previousClip->adminPath());
});

it('has a trigger smil file button if clip has assets', function () {
    $clip = ClipFactory::withAssets(2)->ownedBy(signInRole(Role::MODERATOR))->create();

    get(route('clips.edit', $clip))->assertSee('Trigger smil files');
});

it('list smil files for admins if any', function () {
    $clip = ClipFactory::withAssets(2)->ownedBy(signInRole(Role::ADMIN))->create();
    $clip->addAsset(Asset::create([
        'disk' => 'videos',
        'original_file_name' => 'camera.smil',
        'type' => Content::SMIL(),
        'path' => '/videos/camera.smil',
        'guid' => Str::uuid(),
        'duration' => '0',
        'width' => '0',
        'height' => '0',
    ]));

    get(route('clips.edit', $clip))->assertSee('camera.smil');
});

it('list audio files if any', function () {
    $clip = ClipFactory::withAssets(2)->ownedBy(signInRole(Role::MODERATOR))->create();
    $clip->addAsset(Asset::create([
        'disk' => 'videos',
        'original_file_name' => 'audio.mp3',
        'type' => Content::AUDIO(),
        'path' => '/videos/'.$clip->folder_id.'/audio.mp3',
        'guid' => Str::uuid(),
        'duration' => '120',
        'width' => '0',
        'height' => '0',
    ]));

    get(route('clips.edit', $clip))->assertSee('audio.mp3');
});

it('has an assign series option in clip edit if clip has no series', function () {
    $clip = ClipFactory::ownedBy(signInRole(Role::MODERATOR))->create(['series_id' => 1]);
    get(route('clips.edit', $clip))->assertDontSee('Assign series');

    $clip = ClipFactory::create();
    get(route('clips.edit', $clip))->assertSee('Assign series');
});

it('shows clip image information', function () {
    $clip = ClipFactory::ownedBy(signInRole(Role::MODERATOR))->create();

    get(route('clips.edit', $clip))->assertSee($clip->image->description);
});

it('loads comments component in edit page', function () {
    $clip = ClipFactory::ownedBy(signInRole(Role::MODERATOR))->create();

    get(route('clips.edit', $clip))->assertSeeLivewire('comments-section');
});

it('shows administration comments in edit page', function () {
    $clip = ClipFactory::ownedBy(signInRole(Role::MODERATOR))->create();
    get(route('clips.edit', $clip))->assertSee(__('clip.frontend.comments'));

    Livewire::test(CommentsSection::class, [
        'model' => $clip,
        'type' => 'backend',
    ])
        ->set('content', 'Admin clip comment')
        ->call('postComment')
        ->assertSee('Comment posted successfully')
        ->assertSee('Admin clip comment');
});

it('deletes all clip assets when the clip is deleted', function () {
    Storage::fake('videos');
    $clip = ClipFactory::ownedBy(signInRole(Role::MODERATOR))->create();
    post(route('admin.clips.asset.transferSingle', $clip), ['asset' => FileFactory::videoFile()]);

    Storage::disk('videos')->assertExists($clip->assets->first()->path);

    delete(route('clips.destroy', $clip));

    Storage::disk('videos')->assertMissing($clip->assets->first()->path);
});

it('deletes all clip assets symbolic links', function () {
    Storage::fake('videos');
    Storage::fake('assetsSymLinks');
    $clip = ClipFactory::ownedBy(signInRole(Role::MODERATOR))->create();
    post(route('admin.clips.asset.transferSingle', $clip), ['asset' => FileFactory::videoFile()]);
    $clip->addAcls(collect(Acl::PUBLIC()));
    $asset = $clip->assets()->first();
    $this->artisan('app:update-assets-symbolic-links');

    Storage::disk('assetsSymLinks')->assertExists("{$asset->guid}.".getFileExtension($asset));
    delete(route('clips.destroy', $clip));
    Storage::disk('assetsSymLinks')->assertMissing("{$asset->guid}.".getFileExtension($asset));
});
