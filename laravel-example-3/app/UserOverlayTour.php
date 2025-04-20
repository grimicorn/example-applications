<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserOverlayTour extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'user_id',
        'id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'user',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'dashboard_viewed' => 'boolean',
        'listing_create_viewed' => 'boolean',
        'listing_edit_viewed' => 'boolean',
        'listing_lcs_show_viewed' => 'boolean',
        'listing_historical_financials_edit_viewed' => 'boolean',
        'exchange_space_index_viewed' => 'boolean',
        'exchange_space_show_viewed' => 'boolean',
        'diligence_center_index_viewed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
