<?php

namespace App;

use App\Notifications\SiteNeedsReview;
use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
    protected $appends = ['message', 'link'];

    public function getMessageAttribute()
    {
        if ($this->type === SiteNeedsReview::class) {
            return "<strong>{$this->site->name}</strong> needs review.";
        }
    }

    protected function getLinkAttribute()
    {
        if ($this->type === SiteNeedsReview::class) {
            return $this->site->reviewUrl();
        }
    }

    public function getSiteAttribute()
    {
        return optional(Site::find($this->data['site_id'] ?? null));
    }
}
