<?php

namespace App\Support;

trait HasLinksAttribute
{
    public function getLinksWithProtocolAttribute()
    {
        if ($this->links === null ) {
            return [];
        }
        
        $this->links = array_filter($this->links);
        if (empty($this->links)) {
            return [];
        }

        // add_url_protocol
        return collect($this->links)->map(function ($link) {
            return add_url_protocol($link);
        })->toArray();
    }
}
