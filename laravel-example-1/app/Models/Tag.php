<?php

namespace App\Models;

use App\Models\Location;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Tag extends Model
{
    use HasFactory;
    use HasSlug;

    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope('orderby_name', function (Builder $builder) {
            $builder->orderBy('name');
        });
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function locations()
    {
        return $this->morphedByMany(Location::class, 'taggable');
    }
}
