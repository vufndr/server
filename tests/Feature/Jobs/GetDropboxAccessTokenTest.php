<?php

namespace Tests\Feature\Jobs;

use App\Jobs\Dropbox\GetAccessToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Tests\TestCase;

class GetDropboxAccessTokenTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Event::fake();
    }

    public function testHandle()
    {
        $this->expectException(IdentityProviderException::class);

        $user = User::factory()->create();
        $code = '1234';

        GetAccessToken::dispatch($user, $code);
    }
}
