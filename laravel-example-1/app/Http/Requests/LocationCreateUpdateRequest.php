<?php

namespace App\Http\Requests;

use App\Models\Location;
use App\Models\LocationIcon;
use Illuminate\Validation\Rule;
use App\Domain\Supports\BestVisitTimes;
use Spatie\Enum\Laravel\Rules\EnumRule;
use App\Enums\LocationAccessDifficultyEnum;
use App\Enums\LocationTrafficLevelEnum;
use Illuminate\Foundation\Http\FormRequest;

class LocationCreateUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // @todo Handle authorization
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $sharedRules = [
            'best_time_of_day_to_visit' => [
                'nullable',
                Rule::in(resolve(BestVisitTimes::class)->ofDay()),
            ],
            'best_time_of_year_to_visit' => [
                'nullable',
                Rule::in(resolve(BestVisitTimes::class)->ofYear()),
            ],
            'rating' => 'numeric|gte:1|lte:5|nullable',
            'visited' => 'boolean|nullable',
            'tags' => 'array|nullable',
            'tags.*' => 'regex:/^[a-z0-9\s]+$/i',
                'notes' => 'string|nullable',
                'icon_id' => [
                    'numeric',
                    Rule::in(LocationIcon::all()->pluck('id')),
                    'nullable',
                ],
            'links' => 'array|nullable',
            'links.*.id' => 'required_without:links.*.url',
            'links.*.url' => [
                'required_without:links.*.id',
                function ($attribute, $value, $fail) {
                    if (!is_numeric($value) and !filter_var($value, FILTER_VALIDATE_URL)) {
                        $fail('The '.$attribute.' is invalid.');
                    }
                }
            ],
            'notes' => 'string|nullable',
            'icon_id' => [
                'numeric',
                Rule::in(LocationIcon::all()->pluck('id')),
                'nullable',
            ],
            'access_difficulty' => [
                new EnumRule(LocationAccessDifficultyEnum::class),
                'nullable',
            ],
            'traffic_level' => [
                new EnumRule(LocationTrafficLevelEnum::class),
                'nullable',
            ],
            'walk_distance' => 'nullable|numeric',
        ];

        if ($this->isUpdate()) {
            return array_merge($sharedRules, [
                'name' => 'sometimes|required|string',
                'address' => 'sometimes|required|string',
            ]);
        }

        return array_merge($sharedRules, [
            'name' => 'required|string',
            'address' => 'required|string',
        ]);
    }

    protected function persistLocation()
    {
        $location = $this->location ?? new Location([
            'user_id' => $this->user()->id,
        ]);

        r_collect($this->validated())
            ->except(['address', 'tags', 'links'])
            ->each(function ($value, $key) use (&$location) {
                $location->$key = '' === $value ? null : $value;
            });

        if ($this->has('address')) {
            $location = $location->setGeocodeAttributesFromAddress($this->get('address'));
        }

        $location->save();

        if ($this->has('tags')) {
            $location->syncTags(collect($this->get('tags', []))->toArray());
        }

        if ($this->has('links')) {
            $location->syncLinks(collect($this->get('links', []))->toArray());
        }

        return $location;
    }

    protected function handleRedirect(Location $location)
    {
        if ($this->isUpdate()) {
            return back()->with([
                'success_message' => "Location \"{$location->name}\" updated successfully!",
            ]);
        }

        $redirectTo = $this->has('add_new') ? route('locations.create') : route('locations.index');
        return redirect($redirectTo)->with([
            'success_message' => "Location \"{$location->name}\" created successfully!",
        ]);
    }

    protected function isUpdate()
    {
        return !is_null($this->location);
    }

    public function persist()
    {
        return $this->handleRedirect($this->persistLocation($this->location));
    }
}
