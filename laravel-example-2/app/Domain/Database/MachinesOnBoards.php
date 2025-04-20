<?php
namespace App\Domain\Database;

use App\BoardPod;
use App\Machine;

class MachinesOnBoards
{
    protected $machines;

    public function get()
    {
        if ($this->machines) {
            return $this->machines;
        }

        $machines = Machine::with('pods')->get();
        $podsIds = $machines->pluck('pods')->flatten(1)->pluck('id')->unique();
        $podsOnBoardIds = BoardPod::whereIn('pod_id', $podsIds)->get()->pluck('pod_id');

        return $this->machines = $machines->reject(function ($machine) use ($podsOnBoardIds) {
            return $machine->pods->pluck('id')->filter(function ($id) use ($podsOnBoardIds) {
                return $podsOnBoardIds->contains($id);
            })->isEmpty();
        })->unique('id');
    }
}
