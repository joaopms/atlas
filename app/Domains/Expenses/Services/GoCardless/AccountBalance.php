<?php

namespace App\Domains\Expenses\Services\GoCardless;

final readonly class AccountBalance
{
    public function __construct(
        public string $type,
        public string $referenceDate,
        public string $amount,
        public string $currency,
    ) {}

    public static function fromResponse(array $data): AccountBalance
    {
        return new self(
            $data['balanceType'],
            $data['referenceDate'],
            $data['balanceAmount']['amount'],
            $data['balanceAmount']['currency'],
        );
    }
}
