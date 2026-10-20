<?php

namespace Domains\Expenses\Services\GoCardless;

use App\Domains\Expenses\Services\GoCardless\AccessToken;
use App\Domains\Expenses\Services\GoCardless\GoCardlessService;
use App\Domains\Expenses\Services\GoCardless\Institution;
use App\Domains\Expenses\Services\GoCardless\RefreshToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GoCardlessServiceTest extends TestCase
{
    private GoCardlessService $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new GoCardlessService(new AccessToken('', Carbon::now()));
    }

    #[Test]
    public function gets_a_refresh_token()
    {
        Http::fake([
            '*' => Http::response([
                'access' => 'jwt token goes here',
                'access_expires' => 86400,
                'refresh' => 'jwt token goes here',
                'refresh_expires' => 2592000,
            ]),
        ]);

        $tokens = $this->client->getRefreshToken();

        $this->assertArrayHasKey('refresh', $tokens);
        $this->assertArrayHasKey('access', $tokens);

        $this->assertInstanceOf(RefreshToken::class, $tokens['refresh']);
        $this->assertInstanceOf(AccessToken::class, $tokens['access']);
    }

    #[Test]
    public function gets_an_access_token()
    {
        Http::fake([
            '*' => Http::response([
                'access' => 'jwt token goes here',
                'access_expires' => 86400,
            ]),
        ]);

        $token = $this->client->getAccessToken(new RefreshToken('', Carbon::now()));

        $this->assertInstanceOf(AccessToken::class, $token);
    }

    #[Test]
    public function gets_the_list_of_institutions()
    {
        Http::fake([
            '*' => Http::response([
                [
                    'id' => 'BANK_AAABBPT',
                    'name' => 'Bank A',
                    'bic' => 'AAABBPT',
                    'transaction_total_days' => '365',
                    'countries' => ['PT'],
                    'logo' => 'https://example.com/logo.png',
                    'max_access_valid_for_days' => '180',
                ],
                [
                    'id' => 'BANK_BBBCCPT',
                    'name' => 'Bank B',
                    'bic' => 'BBBCCPT',
                    'transaction_total_days' => '90',
                    'countries' => ['PT', 'ES'],
                    'logo' => 'https://example.com/logo.png',
                    'max_access_valid_for_days' => '180',
                ],
            ]),
        ]);

        $institutions = $this->client->getInstitutions('PT');

        $this->assertCount(2, $institutions);
        $this->assertInstanceOf(Institution::class, $institutions[0]);

        /** * @var Institution $institution */
        $institution = $institutions[0];

        $this->assertEquals('BANK_AAABBPT', $institution->id);
        $this->assertEquals('Bank A', $institution->name);
        $this->assertEquals('AAABBPT', $institution->bic);
        $this->assertEquals('https://example.com/logo.png', $institution->logo);
        $this->assertEquals(365, $institution->transactionDays);
        $this->assertEquals(180, $institution->expirationDays);
    }
}
