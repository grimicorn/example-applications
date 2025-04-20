<?php

namespace App;

use App\BaseModel;
use App\Support\ChecksIsOwner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Favorite extends BaseModel
{
    use ChecksIsOwner;

    /**
     * Get the user that owns the favorite.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the listing that owns the favorite.
     */
    public function listing()
    {
        return $this->belongsTo('App\Listing');
    }

    /**
     * Scopes a query to the
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeOfCurrentUser($query)
    {
        return $query->where('user_id', '=', Auth::id());
    }
}
