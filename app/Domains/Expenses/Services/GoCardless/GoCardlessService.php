<?php

namespace App\Domains\Expenses\Services\GoCardless;

use Domains\Expenses\Services\GoCardless\GoCardlessServiceTest;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

/**
 * Service for GoCardless Bank Account Data - formerly known as Nordigen.
 * https://docs.gocardless.com/docs/bank-account-data
 *
 * @see GoCardlessServiceTest
 */
class GoCardlessService
{
    private const string BASE_URL = 'https://bankaccountdata.gocardless.com/api/v2';

    public function __construct(private readonly ?AccessToken $token = null) {}

    private function getClient(): PendingRequest
    {
        if (! $this->token) {
            throw new \InvalidArgumentException('Token not provided');
        }

        return Http::acceptJson()
            ->withHeader('Authorization', "Bearer {$this->token->token}");
    }

    /**
     * Generates a refresh and an access token using the stored secret ID and key.
     *
     * @throws GoCardlessException
     * @throws ConnectionException
     */
    public function getRefreshToken(): array
    {
        $response = Http::acceptJson()
            ->post(self::BASE_URL.'/token/new/', [
                'secret_id' => config('services.gocardless.secret_id'),
                'secret_key' => config('services.gocardless.secret_key'),
            ]);

        $this->assertSuccess($response);

        return [
            'refresh' => RefreshToken::fromResponse((array) $response->json()),
            'access' => AccessToken::fromResponse((array) $response->json()),
        ];
    }

    /**
     * Generates an access token from a refresh token.
     *
     * @throws GoCardlessException
     * @throws ConnectionException
     */
    public function getAccessToken(RefreshToken $refreshToken): AccessToken
    {
        $response = Http::acceptJson()
            ->post(self::BASE_URL.'/token/refresh/', [
                'refresh' => $refreshToken->token,
            ]);

        $this->assertSuccess($response);

        return AccessToken::fromResponse((array) $response->json());
    }

    /**
     * Gets the list of institutions for an ISO 3166 (two-letter) country.
     *
     * @return Collection<Institution>
     *
     * @throws GoCardlessException
     * @throws ConnectionException
     */
    public function getInstitutions(string $country): Collection
    {
        $response = $this->getClient()
            ->get(self::BASE_URL.'/institutions/', ['country' => $country]);

        $this->assertSuccess($response);

        return collect((array) $response->json())
            ->map(fn (array $item) => Institution::fromResponse($item));
    }

    /**
     * Creates an End User Agreement for an Institution.
     *
     * @throws GoCardlessException
     * @throws ConnectionException
     */
    public function createEndUserAgreement(Institution $institution): EndUserAgreement
    {
        $response = $this->getClient()
            ->post(self::BASE_URL.'/agreements/enduser/', [
                'institution_id' => $institution->id,
                'max_historical_days' => $institution->transactionDays,
                'access_valid_for_days' => $institution->expirationDays,
                'access_scope' => ['balances', 'details', 'transactions'],
            ]);

        $this->assertSuccess($response);

        return EndUserAgreement::fromResponse((array) $response->json());
    }

    /**
     * Creates a Requisition from an End User Agreement.
     *
     * The link must be used by the user to give access to their accounts.
     * After the permission is given, they are redirected to the redirect
     * URL with the query param `ref`. Example: `https://example.com/callback?ref=123`
     *
     * @throws GoCardlessException
     * @throws ConnectionException
     */
    public function createRequisition(EndUserAgreement $endUserAgreement, string $redirectUrl, string $referenceId): Requisition
    {
        $response = $this->getClient()
            ->post(self::BASE_URL.'/requisitions/', [
                'redirect' => $redirectUrl,
                'institution_id' => $endUserAgreement->institutionId,
                'reference' => $referenceId,
                'agreement' => $endUserAgreement->id,
            ])
            ->dump();

        $this->assertSuccess($response);

        return Requisition::fromResponse((array) $response->json());
    }

    /**
     * Gets a Requisition by its ID.
     *
     * @throws GoCardlessException
     * @throws ConnectionException
     */
    public function getRequisition(string $id): Requisition
    {
        $response = $this->getClient()
            ->get(self::BASE_URL.'/requisitions/'.$id);

        $this->assertSuccess($response);

        return Requisition::fromResponse((array) $response->json());
    }

    /**
     * Gets an Account by its ID.
     *
     * @throws GoCardlessException
     * @throws ConnectionException
     */
    public function getAccount(string $id): Account
    {
        $response = $this->getClient()
            ->get(self::BASE_URL.'/accounts/'.$id);

        $this->assertSuccess($response);

        return Account::fromResponse((array) $response->json());
    }

    /**
     * Gets the list of balances for an account ID.
     *
     * @return Collection<AccountBalance>
     *
     * @throws GoCardlessException
     * @throws ConnectionException
     */
    public function getAccountBalances(string $id): Collection
    {
        $response = $this->getClient()
            ->get(self::BASE_URL.'/accounts/'.$id.'/balances/');

        $this->assertSuccess($response);

        return collect((array) $response->json('balances'))
            ->map(fn (array $item) => AccountBalance::fromResponse($item));
    }

    /**
     * @throws GoCardlessException
     */
    private function assertSuccess(Response $response): void
    {
        if (! $response->successful()) {
            throw GoCardlessException::fromResponse((array) $response->json());
        }
    }
}
