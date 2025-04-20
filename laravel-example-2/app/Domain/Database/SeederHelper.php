<?php

namespace App\Domain\Database;

use App\Pod;
use App\Board;
use App\Machine;

class SeederHelper
{
    public function getRandomBoard()
    {
        return Board::inRandomOrder()->first() ?? factory(Board::class)->create();
    }

    public function getRandomMachine()
    {
        return Machine::inRandomOrder()->first() ?? factory(Machine::class)->create()->freshFromUuid();
    }

    public function getRandomPod()
    {
        return Pod::inRandomOrder()->first() ?? factory(Pod::class)->create();
    }
}
