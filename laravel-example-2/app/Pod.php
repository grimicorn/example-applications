<?php

namespace App;

use App\Board;
use App\Machine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pod extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        //
    ];

    public function boards()
    {
        return $this->belongsToMany(Board::class);
    }

    public function machines()
    {
        return $this->belongsToMany(Machine::class);
    }
}
