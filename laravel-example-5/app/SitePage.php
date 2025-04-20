<?php

namespace App;

use App\Site;
use App\SnapshotConfiguration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Traits\LogsActivity;

class SitePage extends Model
{
    use LogsActivity;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected static $logUnguarded = true;

    protected static $logOnlyDirty = true;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'ignored' => 'boolean',
        'difference_threshold' => 'float',
        'needs_review' => 'boolean',
    ];

    protected $appends = [
        'path',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function user()
    {
        return $this->site->user();
    }

    public function setDifferenceThresholdAttribute($value)
    {
        $value = $this->site->standardizeDifferenceThreshold($value);
        $this->attributes['difference_threshold'] = $value;
    }

    public function snapshotConfigurations()
    {
        return $this->hasMany(SnapshotConfiguration::class);
    }

    public function ignore()
    {
        $this->ignored = true;
        $this->save();

        return $this;
    }

    public function removeIgnore()
    {
        $this->ignored = false;
        $this->save();

        return $this;
    }

    public function getPathAttribute()
    {
        return parse_url($this->url, PHP_URL_PATH);
    }

    public function scopeForSite(Builder $query, Site $site)
    {
        return $query->where('site_id', $site->id);
    }

    public function getDifferenceAllowedAttribute()
    {
        if (!is_numeric($this->difference_threshold)) {
            return 0;
        }

        return 1 - $this->difference_threshold;
    }

    public function needsReviewSnapshotConfigurations()
    {
        return $this->snapshotConfigurations()
            ->where('needs_review', true)
            ->get();
    }

    public function setNeedsReviewStatus()
    {
        $this->needs_review = !$this->needsReviewSnapshotConfigurations()->isEmpty();
        $this->save();

        $this->site->setNeedsReviewStatus();

        return $this;
    }
}
