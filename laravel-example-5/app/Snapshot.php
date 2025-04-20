<?php

namespace App;

use App\SnapshotConfiguration;
use App\Support\SnapshotDifference;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Snapshot extends Model implements HasMedia
{
    use HasMediaTrait,
        LogsActivity;

    protected $guarded = [];

    protected static $logUnguarded = true;

    protected static $logOnlyDirty = true;

    protected $casts = [
        'is_baseline' => 'boolean',
        'difference' => 'float',
    ];

    public $with = ['media'];

    public function configuration()
    {
        return $this->belongsTo(
            SnapshotConfiguration::class,
            'snapshot_configuration_id'
        );
    }

    public function getPathAttribute()
    {
        return $this->getFirstMediaPath('snapshot');
    }

    public function registerMediaCollections()
    {
        $this
            ->addMediaCollection('snapshot')
            ->singleFile();

        $this
            ->addMediaCollection('difference')
            ->singleFile();
    }

    public function scopeBaseline(Builder $builder)
    {
        return $builder->where('is_baseline', true)
            ->orderByDesc('updated_at');
    }

    public function scopeNotBaseline(Builder $builder)
    {
        return $builder->where('is_baseline', false)
            ->orderByDesc('updated_at');
    }

    public function setBaseline()
    {
        $this->is_baseline = true;
        $this->save();
    }

    public function setNotBaseline()
    {
        $this->is_baseline = false;
        $this->save();
    }

    public function calculateDifference()
    {
        $this->difference = (new SnapshotDifference($this))->calculate();
        $this->save();

        return $this;
    }

    public function getDifferenceForDisplayAttribute()
    {
        if (!$this->difference) {
            return 0;
        }

        return $this->difference * 100;
    }

    public function overThreshold()
    {
        if (is_null($this->difference)) {
            return false;
        }

        $threshold = $this->page->difference_threshold * 100;
        return (100 - $threshold) <= $this->difference;
    }

    public function getOverThresholdAttribute()
    {
        return $this->overThreshold();
    }

    public function page()
    {
        return $this->configuration->page();
    }
}
