<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\CachedModel;

class BaseModel extends Model
{
    /**
     * Sorts a query in descending order.
     *
     * @param Builder $query
     * @param string $key
     * @return Builder
     */
    public function scopeDescending($query, $key = 'updated_at')
    {
        return $query->orderBy($key, 'desc');
    }

    /**
     * Sorts a query in descending order.
     *
     * @param Builder $query
     * @param string $key
     * @return Builder
     */
    public function scopeAscending($query, $key = 'updated_at')
    {
        return $query->orderBy($key, 'asc');
    }
}
