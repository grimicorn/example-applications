<?php

namespace App\QueryBuilder\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FilterMinRating implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        if ($value and $value > 0) {
            $query->where('rating', '>=', floatval($value));
        }

        if (intval($value) === -1) {
            $query->where('rating', null);
        }
    }
}
