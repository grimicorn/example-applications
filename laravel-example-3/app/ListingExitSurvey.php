<?php

namespace App;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

class ListingExitSurvey extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'listing_id',
        'final_sale_price',
        'overall_experience_rating',
        'overall_experience_feedback',
        'products_services',
        'participant_message',
        'sale_completed',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'listing_id' => 'integer',
        'final_sale_price' => 'integer',
        'overall_experience_rating' => 'integer',
        'sale_completed' => 'boolean',
    ];

    /**
     * Get the listing that owns the exit survey.
     */
    public function listing()
    {
        return $this->belongsTo('App\Listing');
    }
}
