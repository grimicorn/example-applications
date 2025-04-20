<?php

namespace App;

use App\SitePage;
use App\Snapshot;
use App\Support\Snapshooter;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SnapshotConfiguration extends Model
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
        'needs_review' => 'boolean',
        'width' => 'integer',
    ];

    public function page()
    {
        return $this->belongsTo(SitePage::class, 'site_page_id');
    }

    public function snapshots()
    {
        return $this->hasMany(Snapshot::class);
    }

    public function user()
    {
        return $this->page->site->user();
    }

    public function snapshooter()
    {
        return new Snapshooter($this);
    }

    public function updateSnapshot()
    {
        return optional(
            $this->snapshooter()->take()
        );
    }

    public function getBaseline()
    {
        return $this->snapshots()
            ->baseline()
            ->first();
    }

    public function getBaselineMedia()
    {
        return optional(
            $this->getBaseline()
        )->getFirstMedia('snapshot');
    }

    public function getLatestSnapshot()
    {
        return $this->snapshots()
            ->notBaseline()
            ->orderByDesc('updated_at')
            ->with('media')
            ->first();
    }

    public function setNeedsReviewStatus()
    {
        $difference = optional($this->getLatestSnapshot())->difference;

        if (is_null($difference)) {
            $this->needs_review = false;
        } else {
            $this->needs_review = $difference > $this->page->difference_allowed;
        }

        $this->save();

        $this->page->setNeedsReviewStatus();

        return $this;
    }
}
