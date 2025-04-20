<?php

namespace App\Domain\Database\QueryBuilder;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersControlNumber implements Filter
{
    public function __invoke(Builder $query, $value, string $property) : Builder
    {
        return $query->where('control_number_1', 'like', "%{$value}%")
            ->orWhere('control_number_2', 'like', "%{$value}%")
            ->orWhere('control_number_3', 'like', "%{$value}%")
            ->orWhere('control_number_4', 'like', "%{$value}%");
    }
}
