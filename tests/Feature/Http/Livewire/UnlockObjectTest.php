<?php

use App\Enums\Acl;
use App\Http\Livewire\UnlockObject;
use Facades\Tests\Setup\SeriesFactory;
use Livewire\Livewire;

use function Pest\Laravel\get;

it('load component in series page if has a clip with password acl', function () {
    $series = SeriesFactory::withClips(2)->withAssets(1)->create(['password' => '1234QWer']);
    get(route('frontend.series.show', $series))->assertDontSeeLivewire('unlock-object');
    $firstClip = $series->clips()->first();

    $firstClip->addAcls(collect([Acl::PASSWORD()]));

    get(route('frontend.series.show', $series))->assertSeeLivewire('unlock-object');
});

it('has session errors if unlock password is empty', function () {
    $series = SeriesFactory::withClips(2)->withAssets(1)->create(['password' => '1234QWer']);
    Livewire::test(UnlockObject::class, [
        'model' => $series,
    ])->set('password', '')
        ->call('unlock')
        ->assertHasErrors(['password' => 'required']);
});

it('has session errors if unlock password is simple', function () {
    $series = SeriesFactory::withClips(2)->withAssets(1)->create(['password' => '1234QWer']);
    Livewire::test(UnlockObject::class, [
        'model' => $series,
    ])->set('password', '1234')
        ->call('unlock')
        ->assertHasErrors(['password']);
});

it('has session errors if unlock password does not match', function () {
    $series = SeriesFactory::withClips(2)->withAssets(1)->create(['password' => '1234QWer']);
    Livewire::test(UnlockObject::class, [
        'model' => $series,
    ])->set('password', '1234qwER')
        ->call('unlock')
        ->assertHasErrors(['password']);
});

it('redirects if unlock password does match', function () {
    $series = SeriesFactory::withClips(2)->withAssets(1)->create(['password' => '1234QWer']);
    Livewire::test(UnlockObject::class, [
        'model' => $series,
    ])->set('password', '1234QWer')
        ->call('unlock')
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('frontend.series.show', $series));
});
