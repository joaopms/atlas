<?php

namespace App\Domains\Expenses\Services\GoCardless;

readonly class RefreshToken extends Token
{
    protected static function name(): string
    {
        return 'refresh';
    }
}
