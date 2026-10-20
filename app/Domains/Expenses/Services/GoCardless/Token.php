<?php

namespace App\Domains\Expenses\Services\GoCardless;

use Carbon\Carbon;

abstract readonly class Token
{
    public function __construct(
        public string $token,
        public Carbon $expiration,
    ) {}

    abstract protected static function name(): string;

    public static function fromResponse(array $data): static
    {
        $token = $data[static::name()] ?? null;
        $expiration = $data[static::name().'_expires'] ?? null;

        if (! $token || ! $expiration) {
            throw new \InvalidArgumentException('Token or expiration not provided');
        }

        return new static($token, Carbon::now()->addSeconds((int) $expiration));
    }
}
