<?php

namespace App;

use App\Job;
use App\Pod;
use App\Enums\MachineStatus;
use App\StorableEvents\MachineCreated;
use App\StorableEvents\MachineDeleted;
use App\StorableEvents\MachineUpdated;
use Illuminate\Database\Eloquent\Model;
use App\Domain\Concerns\Database\HasUuid;
use App\Domain\Database\MachinesOnBoards;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domain\Concerns\Supports\StoresModelEvents;

class Machine extends Model
{
    use SoftDeletes,
        HasUuid,
        StoresModelEvents;

    protected $storedModelEvents = [
        'created' => MachineCreated::class,
        'updated' => MachineUpdated::class,
        'deleted' => MachineDeleted::class,
    ];

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

    protected $appends = [
        'is_on_board',
    ];

    public function pods()
    {
        return $this->belongsToMany(Pod::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public static function importFromExternalId($id)
    {
        return self::firstOrCreate(
            [
                'external_machine_id' => $id,
            ],
            [
                'name' => $id,
                'status' => MachineStatus::IMPORTED,
            ]
        )->freshFromUuid();
    }

    protected function getIsOnBoardAttribute()
    {
        return !resolve(MachinesOnBoards::class)->get()->where('id', $this->id)->isEmpty();
    }
}
