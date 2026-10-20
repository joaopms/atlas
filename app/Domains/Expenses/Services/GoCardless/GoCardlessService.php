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
     * @throws GoCardlessException
     */
    private function assertSuccess(Response $response): void
    {
        if (! $response->successful()) {
            throw GoCardlessException::fromResponse((array) $response->json());
        }
    }
}
