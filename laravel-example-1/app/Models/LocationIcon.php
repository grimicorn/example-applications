<?php

namespace App\Models;

use App\Models\Location;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LocationIcon extends Model
{
    use HasFactory;
    use HasSlug;

    protected $guarded = [];

    protected $appends = [
        'url'
    ];

    protected static function booted()
    {
        static::addGlobalScope('orderby_name', function (Builder $builder) {
            $builder->orderBy('name');
        });
    }

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class);
    }

    protected function getUrlAttribute()
    {
        return url("/images/google-map-icons/{$this->filename}");
    }
}
