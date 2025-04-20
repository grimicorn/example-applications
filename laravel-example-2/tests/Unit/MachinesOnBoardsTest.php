<?php

namespace Tests\Unit;

use App\Pod;
use App\Board;
use App\Machine;
use Tests\TestCase;
use App\Domain\Database\MachinesOnBoards;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MachinesOnBoardsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_gets_the_machines_on_boards()
    {
        $expected = create(Machine::class, [], 5)->map->freshFromUuid()->each(function ($machine) {
            $pod = create(Pod::class);
            $board = create(Board::class);
            $pod->boards()->attach($board);
            $machine->pods()->attach($pod);

            return $machine;
        });
        create(Machine::class, [], 5)->map->freshFromUuid();

        $machinesOnBoard = resolve(MachinesOnBoards::class)->get();
        $this->assertCount($expected->count(), $machinesOnBoard);
        $this->assertEquals(
            $expected->pluck('id')->sort()->toArray(),
            $machinesOnBoard->pluck('id')->sort()->toArray()
        );
    }
}
