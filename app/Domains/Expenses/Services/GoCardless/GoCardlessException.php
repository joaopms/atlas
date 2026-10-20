<?php

namespace App\Domains\Expenses\Services\GoCardless;

class GoCardlessException extends \Exception
{
    public static function fromResponse(array $data): GoCardlessException
    {
        if ($code = $data['summary'] ?? null) {
            return new self($code.': '.$data['detail'] ?? null);
        }

        return new self('Validation error: '.json_encode($data));
    }
}
