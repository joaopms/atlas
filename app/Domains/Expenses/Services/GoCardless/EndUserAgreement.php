<?php

namespace App\Domains\Expenses\Services\GoCardless;

final readonly class EndUserAgreement
{
    public function __construct(
        public string $id,
        public string $institutionId,
        public int $transactionDays,
        public int $expirationDays
    ) {}

    public static function fromResponse(array $data): EndUserAgreement
    {
        return new self(
            $data['id'],
            $data['institution_id'],
            (int) $data['max_historical_days'],
            (int) $data['access_valid_for_days']
        );
    }
}
