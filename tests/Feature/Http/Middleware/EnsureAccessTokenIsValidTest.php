<?php

namespace Tests\Feature\Http\Middleware;

use App\Enums\Acl;
use Facades\Tests\Setup\ClipFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnsureAccessTokenIsValidTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_throws_an_unknown_type_error_if_url_type_in_not_in_list(): void
    {
        $clip = ClipFactory::withAssets(2)->create(['password' => '1234qwER']);

        $clip->addAcls(collect([Acl::LMS()]));

        $link = '/protector/link/unknowType/1/asdfasdf23FDasdfasdfasdf/6000/studon';

        $this->get($link)->assertNotFound();
    }
}
