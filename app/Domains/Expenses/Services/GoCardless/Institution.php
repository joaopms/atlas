<?php

namespace App\Domains\Expenses\Services\GoCardless;

final readonly class Institution
{
    public function __construct(
        public string $id,
        public string $name,
        public string $bic,
        public string $logo,
        public int $transactionDays,
        public int $expirationDays
    ) {}

    public static function fromResponse(array $data): Institution
    {
        return new self(
            $data['id'],
            $data['name'],
            $data['bic'],
            $data['logo'],
            (int) $data['transaction_total_days'],
            (int) $data['max_access_valid_for_days']
        );
    }
}
