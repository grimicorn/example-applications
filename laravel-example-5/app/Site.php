<?php

namespace App;

use App\User;
use App\SitePage;
use App\Parsers\SitemapXML;
use App\Jobs\UpdateSitePageSnapshot;
use Illuminate\Database\Eloquent\Model;
use App\Jobs\ProcessSiteSnapshotUpdates;
use App\Jobs\FinalizeSiteSnapshotUpdates;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Traits\CausesActivity;

class Site extends Model
{
    use LogsActivity,
        CausesActivity;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['user_id'];

    protected static $logUnguarded = true;

    protected static $logOnlyDirty = true;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'difference_threshold' => 'float',
        'user_id' => 'integer',
        'needs_review' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pages()
    {
        return $this->hasMany(SitePage::class);
    }

    public function syncPages($force = false)
    {
        $original = $this->getOriginal()['sitemap_url'] ?? null;

        if ($original === $this->sitemap_url and !$force) {
            return;
        }

        // Clear out the pages if the sitemap is emptied.
        if (!$this->sitemap_url) {
            $this->pages()->get()->each->delete();
            return;
        }

        $pageUrls = (new SitemapXML($this->sitemap_url))->parse();

        // Remove old page urls
        $this->pages()->whereNotIn('url', $pageUrls)->get()->each->delete();

        // Add new page urls
        $pageUrls->each(function ($url) {
            SitePage::firstOrCreate([
                'site_id' => $this->id,
                'url' => $url,
            ]);
        });

        return $this->fresh();
    }

    public function getDifferenceThresholdAttribute($value)
    {
        if (is_null($value)) {
            $value = 0.95;
        }

        return $value;
    }

    public function setDifferenceThresholdAttribute($value)
    {
        $value = $this->standardizeDifferenceThreshold($value);
        $this->attributes['difference_threshold'] = $value;
    }

    public function standardizeDifferenceThreshold($value)
    {
        if (is_null($value)) {
            return $value;
        }

        $value = floatval($value);
        if ($value === 0 or $value <= 1) {
            return $value;
        }

        $value = $value / 100;
        if ($value > 1) {
            $value = 1;
        }

        return $value;
    }

    public function snapshots()
    {
        return Snapshot::whereIn(
            'snapshot_configuration_id',
            $this->pages->map
                ->snapshotConfigurations->map
                ->pluck('id')->flatten()
        );
    }

    public function getSnapshotsAttribute()
    {
        return $this->snapshots()->get();
    }

    public function baselineSnapshots()
    {
        return $this->snapshots()->baseline();
    }

    public function getBaselineSnapshotsAttribute()
    {
        return $this->baselineSnapshots()->get();
    }

    public function notBaselineSnapshots()
    {
        return $this->snapshots()->notBaseline();
    }

    public function getNotBaselineSnapshotsAttribute()
    {
        return $this->notBaselineSnapshots()->get();
    }

    public function resetBaselineSnapshots()
    {
        $this->snapshots()->get()->each->delete();
        $this->updateSnapshots();
    }

    public function updateSnapshots()
    {
        if ($this->fresh()->processing) {
            return;
        }

        $this->syncPages($force = true);

        $jobs = [];
        $this->pages()->whereNull('processing')
        ->chunk(100, function ($pages) use (&$jobs) {
            $pages->each(function ($page) use (&$jobs) {
                $jobs[] = new UpdateSitePageSnapshot($page);
            });
        });

        $jobs[] = new FinalizeSiteSnapshotUpdates($this);

        ProcessSiteSnapshotUpdates::withChain($jobs)
            ->dispatch($this)
            ->onQueue('snapshots-process');
    }

    public function scopeForUser(Builder $query, User $user)
    {
        return $query->where('user_id', $user->id);
    }

    public function needsReviewPages()
    {
        return $this->pages()->where('needs_review', true)->get();
    }

    public function setNeedsReviewStatus()
    {
        $this->needs_review = !$this->needsReviewPages()->isEmpty();
        $this->save();

        return $this;
    }

    public function reviewUrl()
    {
        return route('sites.show', [
            'site' => $this,
            'sort' => '-needs_review',
            'page' => 1,
        ]);
    }
}
