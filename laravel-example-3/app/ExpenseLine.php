<?php

namespace App;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

class ExpenseLine extends BaseModel
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'year' => 'date',
    ];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['expense'];

    public function expense()
    {
        return $this->belongsTo('App\Expense');
    }
}
