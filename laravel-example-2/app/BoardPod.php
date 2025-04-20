<?php

namespace App;

use App\Pod;
use App\Board;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BoardPod extends Pivot
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        //
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];

    /**
     * Get the Pod for the BoardPod.
     */
    public function pod()
    {
        return $this->belongsTo(Pod::class);
    }

    /**
     * Get the Board for the BoardPod.
     */
    public function board()
    {
        return $this->belongsTo(Board::class);
    }
}
