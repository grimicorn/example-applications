<?php

namespace App;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Support\HistoricalFinancial\HasYearlyDataHelpers;

class Expense extends BaseModel
{
    use HasYearlyDataHelpers, SoftDeletes;

    public $fillable = [
        'name',
        'amount'
    ];

    public $casts = [
        'name' => 'string',
        'amount' => 'number',
        'historical_financial_id' => 'number'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['listingSpaces'];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['listingSpaces'];

    public function listingSpaces()
    {
        return $this->listing->spaces();
    }

    public function listing()
    {
        return $this->belongsTo('App\Listing');
    }

    public function lines()
    {
        return $this->hasMany('App\ExpenseLine');
    }

    public function linesInYearRange()
    {
        $yearStart = $this->listing->hfYearStart();
        return $this->lines->filter(function ($line) use ($yearStart) {
            return $this->yearRange($yearStart)->contains($line->year->format('Y'));
        })->sortBy(function ($line) {
            return intval($line->year->format('Y'));
        })->values();
    }

    public function hasInputValues()
    {
        return $this->linesInYearRange()->pluck('amount')->sum() > 0;
    }
}
