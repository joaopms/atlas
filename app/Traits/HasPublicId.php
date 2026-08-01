<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Sqids\Sqids;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait HasPublicId
{
    const string COCKFORD_ALPHABET = '0123456789ABCDEFGHJKMNPQRSTVWXYZ';

    protected static function bootHasPublicId(): void
    {
        static::created(fn (Model $model) => self::setPublicId($model));
    }

    private static function setPublicId(Model $model): void
    {
        $squids = new Sqids(self::COCKFORD_ALPHABET, 4);

        $model->forceFill([
            'public_id' => self::publicIdIdentifier().$squids->encode([$model->getAttribute('id')]),
        ])->save();
    }

    protected static function publicIdIdentifier(): string
    {
        throw new \RuntimeException('Public ID identifier not set');
    }
}
