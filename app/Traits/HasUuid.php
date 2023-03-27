<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUuid
{
    public static function bootHasUuid(): void
    {
        self::creating(
            function (self $model): void {
                if ($model->uuid) {
                    return;
                }

                $model->uuid = Str::uuid()->toString();
            }
        );
    }
}
