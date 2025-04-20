<?php

namespace App\QueryBuilder\Filters;

use App\Models\Location;
use App\Models\Tag;
use App\Models\Taggable;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FilterTags implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        if ($value) {
            $taggableIds = Taggable::whereIn('tag_id', $value)
                ->where('taggable_type', Location::class)
                ->get(['taggable_id']);
            $query->whereIn('id', $taggableIds);
        }
    }
}
