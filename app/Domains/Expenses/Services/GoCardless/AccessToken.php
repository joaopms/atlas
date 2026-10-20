<?php

namespace App\Domains\Expenses\Services\GoCardless;

readonly class AccessToken extends Token
{
    protected static function name(): string
    {
        return 'access';
    }
}
