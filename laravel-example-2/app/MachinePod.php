<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class MachinePod extends Pivot
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
     * Get the Pod for the MachinePod.
     */
    public function pod()
    {
        return $this->belongsTo(Pod::class);
    }

    /**
     * Get the Machine for the MachinePod.
     */
    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }
}
