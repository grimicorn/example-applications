<?php

namespace App\Domain\Concerns\Database;

use Illuminate\Database\Eloquent\Model;

trait HasUuid
{
    public static function uuid(string $uuid): ?Model
    {
        return static::where('uuid', $uuid)->first();
    }

    public function freshFromUuid(?string $uuid = null)
    {
        return $this->where('uuid', $this->uuid ?? $uuid)->first();
    }
}
