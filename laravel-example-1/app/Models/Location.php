<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Tag;
use App\Models\Link;
use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use Spatie\Sluggable\HasSlug;
use App\Domain\Supports\Geocoder;
use App\Domain\Supports\Timezone;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Domain\Supports\Location as GeocoderLocation;
use App\Domain\Concerns\SyncsPolymorphicRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasSlug;
    use HasFactory;
    use Searchable;
    use SyncsPolymorphicRelationships;


    protected $guarded = [];

    protected $casts = [
        'latitude' =>  'float',
        'longitude' =>  'float',
        'visited' => 'boolean',
        'rating' => 'float',
    ];

    protected $appends = [
        'icon',
        'sunrise_blue_hour',
        'sunrise',
        'sunrise_golden_hour',
        'sunset',
        'sunset_blue_hour',
        'sunset_golden_hour',
        'distance_from_address',
        'address',
    ];


    protected static function booted()
    {
        static::addGlobalScope('name', function (Builder $builder) {
            $builder->orderBy('name');
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setGeocodeAttributesFromAddress($address)
    {
        $geocode = resolve(Geocoder::class)->enable()->geocodeAddress($address);
        $components = resolve(Geocoder::class)
            ->getAddressForCoordinates(
                $latitude = $geocode->get('lat'),
                $longitude = $geocode->get('lng')
            )
            ->get('address_components', collect());

        $attributes = collect([
            'route',
            'locality',
            'administrative_area_level_1',
            'country',
            'postal_code',
        ])->mapWithKeys(function ($key) use ($components) {
            return [
                $key => optional($components->filter(function ($item) use ($key) {
                    return in_array($key, $item->types);
                })->first()),
            ];
        });

        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->timezone = (new Timezone)->fromCoordinates($latitude, $longitude);
        $this->route = $attributes->get('route', collect())->long_name;
        $this->locality = $attributes->get('locality', collect())->long_name;
        $this->administrative_area_level_1_abbreviation = $attributes->get('administrative_area_level_1', collect())->short_name;
        $this->administrative_area_level_1 = $attributes->get('administrative_area_level_1', collect())->long_name;
        $this->country = $attributes->get('country', collect())->short_name;
        $this->postal_code = $attributes->get('postal_code', collect())->long_name;
        $urlEncodedAddress = urlencode($this->address);
        $this->google_maps_link = "https://www.google.com/maps/dir//{$urlEncodedAddress}/@{$latitude},{$longitude},13z/";

        return $this;
    }

    protected function getAddressAttribute()
    {
        $parts = collect([
            $this->route,
            $this->locality,
            "{$this->administrative_area_level_1} {$this->postal_code}",
            $this->country,
        ])
            ->filter(function ($part) {
                return trim($part);
            });

        return $parts->isEmpty() ? null : $parts->implode(', ');
    }

    public function distanceFromAddress(?string $address): ?string
    {
        if (! $address) {
            return null;
        }

        $geocoder = new Geocoder;
        $coordinates = $geocoder->geocodeAddress($address);
        if ($coordinates->get('formatted_address') === 'result_not_found') {
            return null;
        }

        return $geocoder->distanceBetweenLocations(
            new GeocoderLocation($coordinates->get('lng'), $coordinates->get('lat')),
            new GeocoderLocation($this->longitude, $this->latitude)
        );
    }

    protected function getSunriseAttribute()
    {
        $sunrise = date_sun_info(now()->timestamp, $this->latitude, $this->longitude)['sunrise'];
        if (! $sunrise) {
            return;
        }

        return Carbon::createFromTimestamp($sunrise)->setTimezone($this->timezone);
    }

    protected function getSunriseGoldenHourAttribute()
    {
        if (! $this->sunrise) {
            return;
        }

        return Carbon::createFromTimestamp($this->sunrise->timestamp)
            ->addMinutes(30)
            ->setTimezone($this->timezone);
    }

    protected function getSunriseBlueHourAttribute()
    {
        if (! $this->sunrise) {
            return;
        }

        return Carbon::createFromTimestamp($this->sunrise->timestamp)
            ->subMinutes(30)
            ->setTimezone($this->timezone);
    }

    protected function getSunsetAttribute()
    {
        $sunrise = date_sun_info(now()->timestamp, $this->latitude, $this->longitude)['sunset'];
        if (! $sunrise) {
            return;
        }

        return Carbon::createFromTimestamp($sunrise)->setTimezone($this->timezone);
    }

    protected function getSunsetGoldenHourAttribute()
    {
        if (! $this->sunset) {
            return;
        }

        return Carbon::createFromTimestamp($this->sunset->timestamp)
            ->setTimezone($this->timezone)
            ->subMinutes(30);
    }

    protected function getSunsetBlueHourAttribute()
    {
        if (! $this->sunset) {
            return;
        }

        return Carbon::createFromTimestamp($this->sunset->timestamp)
            ->setTimezone($this->timezone)
            ->addMinutes(30);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function links()
    {
        return $this->morphToMany(Link::class, 'linkable');
    }

    public function storeTaxonomyByNames(array $names, string $taxonomyName)
    {
        $names = array_filter($names);
        if (! $names) {
            return $this;
        }

        $taxonomyRelationship = Str::camel(Str::plural($taxonomyName));
        $currentTaxonomies = $this->$taxonomyRelationship->pluck(['id']);
        $taxonomyClass = 'App\\Models\\'.Str::studly($taxonomyName);
        $taxonomies = collect($names)->map(function ($name) use ($taxonomyClass) {
            return $taxonomyClass::firstOrCreate([
                'name' => $name,
            ]);
        })
            ->unique('id')
            ->reject(function ($taxonomy) use ($currentTaxonomies) {
                return $currentTaxonomies->contains($taxonomy->id);
            });

        $this->$taxonomyRelationship()->saveMany($taxonomies);

        return $this;
    }

    public function syncTags(array $ids)
    {
        $ids = collect($ids)
            ->map(function ($id) {
                if (is_numeric($id)) {
                    return $id;
                }

                return Tag::firstOrCreate([
                    'name' => $id,
                ])->id;
            });

        return $this->syncPolymorphicRelationship(
            $ids->toArray(),
            $relationship = 'tags',
            $model = Tag::class
        );
    }

    public function syncLinks(array $links)
    {
        // @todo Clean this up (It works but it's complex)
        $links = collect($links)->reject(function ($link) {
            return ! $link;
        });

        // @todo Abstract existing somewhere?
        $existingModels = Link::whereIn('id', $links->pluck('id'))->get();
        $existingIds = $links->filter(fn ($link) => !is_null($link['id'] ?? null))
            ->map(function ($link) use ($existingModels) {
                $id = $link['id'];
                $url = $link['url'] ?? null;
                $name = $link['name'] ?? $url;
                $currentLink = $existingModels->where('id', $id)->first();

                if ($currentLink->url === $url and $currentLink->name === $name) {
                    return $id;
                }

                $currentLink->name = $name ?? $url;
                $currentLink->url = $url;
                $currentLink->save();

                return $id;
            });

        // @todo Abstract new somewhere?
        $newIds = $links->filter(fn ($link) => is_null($link['id'] ?? null))
            ->map(function ($link) {
                $url = $link['url'] ?? null;
                $name = $link['name'] ?? $url;

                if (!$url) {
                    return;
                }

                return Link::firstOrCreate([
                    'url' => $url,
                    'name' => $name,
                ])->id;
            });

        return $this->syncPolymorphicRelationship(
            $existingIds->merge($newIds)->toArray(),
            $relationship = 'links',
            $model = Link::class
        );
    }

    protected function getIconAttribute()
    {
        return LocationIcon::find($this->icon_id);
    }

    public function icon()
    {
        return $this->hasOne(LocationIcon::class);
    }

    protected function getDistanceFromAddressAttribute()
    {
        $address = request('address');

        return $address ? $this->distanceFromAddress($address) : null;
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
}
