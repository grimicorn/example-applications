<?php

namespace App;

use App\Media;
use App\Machine;
use Carbon\Carbon;
use App\Enums\JobFlag;
use App\Enums\JobType;
use App\Enums\Placement;
use App\Enums\WipStatus;
use Illuminate\Support\Str;
use App\Scopes\DueAtOrderScope;
use App\StorableEvents\JobCreated;
use App\StorableEvents\JobDeleted;
use App\StorableEvents\JobUpdated;
use Illuminate\Database\Eloquent\Model;
use App\Domain\Concerns\Database\HasUuid;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use App\StorableEvents\JobPrintDetailDeleted;
use App\StorableEvents\JobPrintDetailUpdated;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use App\Domain\Concerns\Supports\StoresModelEvents;

class Job extends Model implements HasMedia
{
    use HasMediaTrait,
        SoftDeletes,
        StoresModelEvents,
        HasUuid;

    protected $attributes = [
        'sort_order' => 0,
    ];

    protected $storedModelEvents = [
        'created' => JobCreated::class,
        'updated' => JobUpdated::class,
        'deleted' => JobDeleted::class,
    ];

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        //
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];

    protected $with = [
        'media',
    ];

    protected $casts = [
            'screens_1' => 'integer',
            'screens_2' => 'integer',
            'screens_3' => 'integer',
            'screens_4' => 'integer',
            'priority' => 'integer',
            'total_quantity' => 'integer',
            'small_quantity' => 'integer',
            'medium_quantity' => 'integer',
            'large_quantity' => 'integer',
            'xlarge_quantity' => 'integer',
            '2xlarge_quantity' => 'integer',
            'other_quantity' => 'integer',
            'complete_count' => 'integer',
            'issue_count' => 'integer',
            'type' => 'integer',
            'duration' => 'integer',
            'sort_order' => 'integer',
            'garment_ready' => 'boolean',
            'screens_ready' => 'boolean',
            'ink_ready' => 'boolean',
    ];

    protected $dates = [
        'start_at',
        'due_at',
        'started_at',
        'completed_at',
    ];

    protected $appends = [
        'is_specialty',
        'control_numbers_label',
        'impressions_count',
        'screens_count',
        'print_detail',
        'is_ready',
        'front_screen_count',
        'back_screen_count',
        'other_screen_count',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new DueAtOrderScope);
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function registerMediaCollections()
    {
        $this
            ->addMediaCollection('print_detail')
            ->singleFile();
    }

    protected function getPrintDetailAttribute()
    {
        return $this->getFirstMedia('print_detail');
    }

    /**
     * Handles the print detail upload
     */
    public function updatePrintDetailFromAttributes(array $validated)
    {
        $params = collect($validated);

        if (!$params->has('print_detail')) {
            return $this;
        }

        if ($printDetail = $params->get('print_detail')) {
            $previous = optional($this->print_detail)->toArray() ?: [];
            $media = $this->addMedia($printDetail)->toMediaCollection('print_detail');
            event(new JobPrintDetailUpdated($this->uuid, $media->toArray(), $previous));

            return $this->fresh();
        }

        if (!$this->print_detail) {
            return $this;
        }

        $media = $this->print_detail;
        $this->print_detail->delete();
        event(new JobPrintDetailDeleted($this->uuid, $media->toArray() ?? []));

        return $this->fresh();
    }

    protected function getControlNumbersAttribute()
    {
        return collect([
            $this->control_number_1,
            $this->control_number_2,
            $this->control_number_3,
            $this->control_number_4,
        ]);
    }

    protected function getControlNumbersLabelAttribute()
    {
        return $this->control_numbers->filter()->implode(', ');
    }

    protected function getIsSpecialtyAttribute()
    {
        return $this->type !== JobType::DEFAULT;
    }

    protected function getAppends()
    {
        return $this->appends;
    }

    protected function getFlagSlugAttribute()
    {
        if (is_null($this->flag)) {
            return '';
        }

        $name = JobFlag::displayNameForKey($this->flag);
        return Str::slug($name);
    }

    protected function getImpressionsCountAttribute()
    {
        return $this->total_quantity * $this->screens_count;
    }

    protected function getScreensCountAttribute()
    {
        return intval($this->screens_1) +
            intval($this->screens_2) +
            intval($this->screens_3) +
            intval($this->screens_4);
    }

    protected function getIsReadyAttribute()
    {
        return $this->wip_status === WipStatus::K;
    }

    protected function placementCounts()
    {
        return collect([
            [
                'placement' => $this->placement_1,
                'count' => $this->screens_1,
            ],

            [
                'placement' => $this->placement_2,
                'count' => $this->screens_2,
            ],

            [
                'placement' => $this->placement_3,
                'count' => $this->screens_3,
            ],

            [
                'placement' => $this->placement_4,
                'count' => $this->screens_4,
            ],
        ]);
    }

    protected function getPlacementCount($placement)
    {
        return $this->placementCounts()
            ->where('placement', $placement)
            ->pluck('count')
            ->sum();
    }

    protected function getFrontScreenCountAttribute()
    {
        return $this->getPlacementCount(Placement::FRONT);
    }

    protected function getBackScreenCountAttribute()
    {
        return $this->getPlacementCount(Placement::BACK);
    }

    protected function getOtherScreenCountAttribute()
    {
        return $this->placementCounts()
            ->whereNotIn('placement', [
                Placement::FRONT,
                Placement::BACK,
            ])
            ->pluck('count')
            ->sum();
    }

    protected function setDueAtAttribute($value)
    {
        if (is_string($value)) {
            $value = Carbon::createFromFormat('Y-m-d H:i:s', $value);
        }

        if ($value instanceof Carbon) {
            $value = $value->endOfDay()->format('Y-m-d H:i:s');
        }

        $this->attributes['due_at'] = $value;
    }

    protected function setStartAtAttribute($value)
    {
        if (is_string($value)) {
            $value = Carbon::createFromFormat('Y-m-d H:i:s', $value);
        }

        if ($value instanceof Carbon) {
            $value = $value->startOfDay()->format('Y-m-d H:i:s');
        }

        $this->attributes['start_at'] = $value;
    }
}
