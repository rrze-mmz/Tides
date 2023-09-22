<?php

use App\Enums\Acl;
use Facades\Tests\Setup\ClipFactory;

use function Pest\Laravel\get;

it('throws an unknown type error if url type in not in list', function () {
    $clip = ClipFactory::withAssets(2)->create(['password' => '1234qwER']);

    $clip->addAcls(collect([Acl::LMS()]));

    $link = '/protector/link/unknowType/1/asdfasdf23FDasdfasdfasdf/6000/studon';

    get($link)->assertNotFound();
});
