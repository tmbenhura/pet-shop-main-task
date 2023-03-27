<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUuid
{
    public static function bootHasUuid(): void
    {
        self::creating(
            function (self $model): void {
                $model->uuid = Str::uuid()->toString();
            }
        );
    }
}
