<?php

namespace App\Support;

trait ChecksIsOwner
{
    public function isOwner()
    {
        if (!auth()->check()) {
            return false;
        }

        return intval(auth()->id()) === intval($this->user_id);
    }

    public function getIsOwnerAttribute()
    {
        return $this->isOwner();
    }
}
