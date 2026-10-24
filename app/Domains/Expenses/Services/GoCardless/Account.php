<?php

namespace App\Domains\Expenses\Services\GoCardless;

final readonly class Account
{
    public function __construct(
        public string $id,
        public string $institutionId,
        public string $name,
        public string $ownerName,
        public string $iban,
        public string $status,
    ) {}

    public static function fromResponse(array $data): Account
    {
        return new self(
            $data['id'],
            $data['institution_id'],
            $data['name'],
            $data['owner_name'],
            $data['iban'],
            $data['status'],
        );
    }
}
