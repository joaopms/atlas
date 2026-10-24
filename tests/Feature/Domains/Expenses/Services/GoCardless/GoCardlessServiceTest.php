<?php

namespace Domains\Expenses\Services\GoCardless;

use App\Domains\Expenses\Services\GoCardless\AccessToken;
use App\Domains\Expenses\Services\GoCardless\Account;
use App\Domains\Expenses\Services\GoCardless\AccountBalance;
use App\Domains\Expenses\Services\GoCardless\EndUserAgreement;
use App\Domains\Expenses\Services\GoCardless\GoCardlessService;
use App\Domains\Expenses\Services\GoCardless\Institution;
use App\Domains\Expenses\Services\GoCardless\RefreshToken;
use App\Domains\Expenses\Services\GoCardless\Requisition;
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

    #[Test]
    public function creates_an_end_user_agreement()
    {
        Http::fake([
            '*' => Http::response([
                'id' => '23b0ee87-b220-4969-abb8-38211bcd58cc',
                'created' => '2026-10-01T00:00:00.123456Z',
                'institution_id' => 'SANDBOXFINANCE_SFIN0000',
                'max_historical_days' => 90,
                'access_valid_for_days' => 180,
                'access_scope' => [
                    'balances',
                    'details',
                    'transactions',
                ],
                'accepted' => null,
                'reconfirmation' => false,
            ]),
        ]);

        $institution = new Institution(
            'SANDBOXFINANCE_SFIN0000',
            '',
            '',
            '',
            90,
            180
        );

        $endUserAgreement = $this->client->createEndUserAgreement($institution);

        $this->assertInstanceOf(EndUserAgreement::class, $endUserAgreement);

        $this->assertEquals('23b0ee87-b220-4969-abb8-38211bcd58cc', $endUserAgreement->id);
        $this->assertEquals($institution->id, $endUserAgreement->institutionId);
        $this->assertEquals($institution->transactionDays, $endUserAgreement->transactionDays);
        $this->assertEquals($institution->expirationDays, $endUserAgreement->expirationDays);
    }

    #[Test]
    public function creates_a_requisition()
    {
        Http::fake([
            '*' => Http::response([
                'id' => '18f0642a-30f1-4d8e-ad97-2af7e306a09c',
                'created' => '2026-10-01T00:00:00.123456Z',
                'redirect' => 'https://example.com/callback',
                'status' => 'CR',
                'institution_id' => 'SANDBOXFINANCE_SFIN0000',
                'agreement' => '23b0ee87-b220-4969-abb8-38211bcd58cc',
                'reference' => '123',
                'accounts' => [],
                'link' => 'https://ob.gocardless.com/ob-psd2/start/e1b61ae5-60d0-46f2-800d-d132c5f63300/SANDBOXFINANCE_SFIN0000',
                'ssn' => null,
                'account_selection' => false,
                'redirect_immediate' => false,
            ]),
        ]);

        $endUserAgreement = new EndUserAgreement(
            '23b0ee87-b220-4969-abb8-38211bcd58cc',
            'SANDBOXFINANCE_SFIN0000',
            90,
            180
        );

        $requisition = $this->client->createRequisition($endUserAgreement, 'https://example.com/callback', '123');

        $this->assertInstanceOf(Requisition::class, $requisition);

        $this->assertEquals('18f0642a-30f1-4d8e-ad97-2af7e306a09c', $requisition->id);
        $this->assertEquals('SANDBOXFINANCE_SFIN0000', $requisition->institutionId);
        $this->assertEquals('23b0ee87-b220-4969-abb8-38211bcd58cc', $requisition->agreementId);
        $this->assertEquals('CR', $requisition->status);
        $this->assertEquals('123', $requisition->reference);
        $this->assertEquals('https://ob.gocardless.com/ob-psd2/start/e1b61ae5-60d0-46f2-800d-d132c5f63300/SANDBOXFINANCE_SFIN0000', $requisition->link);
    }

    #[Test]
    public function gets_a_requisition()
    {
        Http::fake([
            '*' => Http::response([
                'id' => '18f0642a-30f1-4d8e-ad97-2af7e306a09c',
                'created' => '2026-10-01T00:00:00.123456Z',
                'redirect' => 'https://example.com/callback',
                'status' => 'LN',
                'institution_id' => 'SANDBOXFINANCE_SFIN0000',
                'agreement' => '23b0ee87-b220-4969-abb8-38211bcd58cc',
                'reference' => '123',
                'accounts' => [
                    'c51caa55-d6f3-4c85-b5af-eb33ff36a5e4',
                    'eaecf9e2-4717-4408-a9e6-3919b4447808',
                ],
                'link' => 'https://ob.gocardless.com/ob-psd2/start/e1b61ae5-60d0-46f2-800d-d132c5f63300/SANDBOXFINANCE_SFIN0000',
                'ssn' => null,
                'account_selection' => false,
                'redirect_immediate' => false,
            ]),
        ]);

        $requisition = $this->client->getRequisition('18f0642a-30f1-4d8e-ad97-2af7e306a09c');

        $this->assertInstanceOf(Requisition::class, $requisition);

        $this->assertEquals('18f0642a-30f1-4d8e-ad97-2af7e306a09c', $requisition->id);
        $this->assertEquals('SANDBOXFINANCE_SFIN0000', $requisition->institutionId);
        $this->assertEquals('23b0ee87-b220-4969-abb8-38211bcd58cc', $requisition->agreementId);
        $this->assertEquals('LN', $requisition->status);
        $this->assertEquals('123', $requisition->reference);
        $this->assertEquals('https://ob.gocardless.com/ob-psd2/start/e1b61ae5-60d0-46f2-800d-d132c5f63300/SANDBOXFINANCE_SFIN0000', $requisition->link);
        $this->assertEquals(['c51caa55-d6f3-4c85-b5af-eb33ff36a5e4', 'eaecf9e2-4717-4408-a9e6-3919b4447808'], $requisition->accountIds);
    }

    #[Test]
    public function gets_an_account()
    {
        Http::fake([
            '*' => Http::response([
                'id' => 'c51caa55-d6f3-4c85-b5af-eb33ff36a5e4',
                'created' => '2024-08-29T01:09:51.084356Z',
                'last_accessed' => '2025-05-23T20:07:35.673676Z',
                'iban' => 'GL7010320000010327',
                'institution_id' => 'SANDBOXFINANCE_SFIN0000',
                'status' => 'READY',
                'owner_name' => 'John Doe',
                'bban' => null,
                'name' => 'Main Account',
            ]),
        ]);

        $account = $this->client->getAccount('c51caa55-d6f3-4c85-b5af-eb33ff36a5e4');

        $this->assertInstanceOf(Account::class, $account);

        $this->assertEquals('c51caa55-d6f3-4c85-b5af-eb33ff36a5e4', $account->id);
        $this->assertEquals('SANDBOXFINANCE_SFIN0000', $account->institutionId);
        $this->assertEquals('Main Account', $account->name);
        $this->assertEquals('John Doe', $account->ownerName);
        $this->assertEquals('GL7010320000010327', $account->iban);
        $this->assertEquals('READY', $account->status);
    }

    #[Test]
    public function gets_account_balances()
    {
        Http::fake([
            '*' => Http::response([
                'balances' => [
                    [
                        'balanceAmount' => ['amount' => '1913.12', 'currency' => 'EUR'],
                        'balanceType' => 'expected',
                        'referenceDate' => '2026-10-01',
                    ],
                    [
                        'balanceAmount' => ['amount' => '1913.12', 'currency' => 'EUR'],
                        'balanceType' => 'interimAvailable',
                        'referenceDate' => '2026-10-01',
                    ],
                ],

            ]),
        ]);

        $balances = $this->client->getAccountBalances('c51caa55-d6f3-4c85-b5af-eb33ff36a5e4');

        $this->assertCount(2, $balances);
        $this->assertInstanceOf(AccountBalance::class, $balances[0]);

        /** * @var AccountBalance $balance */
        $balance = $balances[0];

        $this->assertEquals('expected', $balance->type);
        $this->assertEquals('2026-10-01', $balance->referenceDate);
        $this->assertEquals('1913.12', $balance->amount);
        $this->assertEquals('EUR', $balance->currency);
    }
}
