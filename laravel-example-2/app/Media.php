<?php

namespace App;

use Spatie\MediaLibrary\Models\Media as BaseMedia;

class Media extends BaseMedia
{
    protected $appends = [
        'full_url',
    ];

    protected function getFullUrlAttribute()
    {
        return $this->getFullUrl();
    }
}
