<?php

namespace App\Domains\Expenses\Services\GoCardless;

final readonly class Requisition
{
    public function __construct(
        public string $id,
        public string $institutionId,
        public string $agreementId,
        public string $status,
        public string $reference,
        public string $link,
        public ?array $accountIds = []
    ) {}

    public static function fromResponse(array $data): Requisition
    {
        return new self(
            $data['id'],
            $data['institution_id'],
            $data['agreement'],
            $data['status'],
            $data['reference'],
            $data['link'],
            $data['accounts'],
        );
    }
}
