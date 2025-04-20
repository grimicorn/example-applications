<?php

namespace App\Http\Requests;

use App\Models\Location;
use App\Domain\Supports\Geocoder;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\QueryBuilder\Filters\FilterTags;
use App\QueryBuilder\Sorts\DistanceSort;
use Illuminate\Foundation\Http\FormRequest;
use App\QueryBuilder\Filters\FilterMinRating;
use App\QueryBuilder\Filters\FilterMaxDistance;

class LocationIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // @todo Authorization
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'filter.tags' => 'array|nullable',
            'filter.visited' => 'boolean|nullable',
            'filter.max_distance' => 'numeric|nullable',
            'filter.min_rating' => 'numeric|nullable',
            'map' => 'boolean|nullable',
            'per_page' => 'numeric|nullable',
            'search' => 'string|nullable',
            'address' => 'string|nullable',
            'sort' => 'string|nullable',
            'filter.tags' => 'array|nullable'
        ];
    }

    protected function queryBuilder()
    {
        return QueryBuilder::for(Location::class)
            ->defaultSort('-created_at')
            ->allowedSorts([
                AllowedSort::custom('distance', new DistanceSort()),
            ])
            ->allowedFilters([
                AllowedFilter::custom('max_distance', new FilterMaxDistance),
                AllowedFilter::custom('min_rating', new FilterMinRating),
                AllowedFilter::custom('tags', new FilterTags),
                AllowedFilter::exact('visited'),
            ])
            ->where('user_id', request()->user()->id);
    }

    protected function handleRedirect($data)
    {
        if ($this->expectsJson()) {
            return [
                'data' => $data,
            ];
        }

        return view('locations.index', $data);
    }

    protected function perPage($default = 15)
    {
        $validated = r_collect($this->validated());

        return is_numeric($validated->get('per_page')) ? intval($validated->get('per_page')) : $default;
    }

    public function filter()
    {
        $parameters = collect($this->validated());
        $query = $this->queryBuilder();
        if ($search = $parameters->get('search')) {
            // We want to limit the possible amount of ids to a high but manageable number.
            $searchIds = Location::search($search)->take(1000)->keys();
            $query->whereIn('id', $searchIds);
        }

        $locations = $query
            ->with('tags', 'links')
            ->paginate($this->perPage())
            ->appends($parameters->toArray());

        return $this->handleRedirect([
            'locations' => $locations,
            'coordinates' => resolve(Geocoder::class)->enable()->geocodeAddress(request('address')),
            'searchAddressDistance' => $this->user()->search_address_distance,
            'searchLocations' => $this->user()->searchLocations
        ]);
    }
}
