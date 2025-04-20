<?php

namespace Tests\Unit;

use App\Pod;
use App\Board;
use App\Machine;
use Tests\TestCase;
use App\Domain\StoredEvent;
use Illuminate\Support\Facades\Event;
use App\StorableEvents\MachineCreated;
use App\StorableEvents\MachineDeleted;
use App\StorableEvents\MachineUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MachineTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_fires_a_machine_created_event_when_creating_a_machine()
    {
        $this->signInDeveloper();

        Event::fake([
            MachineCreated::class,
        ]);

        $attributes = array_filter(make(Machine::class, ['uuid' => null])->toArray());
        $machine = Machine::create($attributes);
        $attributes['uuid'] = $machine->uuid;

        Event::assertDispatched(MachineCreated::class, function ($event) use ($attributes) {
            $this->assertEquals($attributes, $event->attributes);
            $this->assertEquals($event->attributes['uuid'], $event->uuid);

            return true;
        });
    }

    /** @test */
    public function it_stores_a_machine_created_event_when_creating_a_machine()
    {
        $user = $this->signInAdmin();

        $attributes = collect(make(Machine::class, ['uuid' => null])->toArray())->filter();
        $machine = Machine::create($attributes->toArray())->freshFromUuid();

        $event = StoredEvent::where('event_class', MachineCreated::class)->first();
        $attributes = collect($attributes)->put('uuid', $machine->uuid);
        $this->assertEquals($machine->uuid, $event->event_properties['uuid']);
        $this->assertEquals($attributes->toArray(), $event->event_properties['attributes']);
        $this->assertEquals($user->id, $event->meta_data['user_id']);
        $this->assertArraySubset($attributes->toArray(), $machine->fresh()->toArray());
    }

    /** @test */
    public function it_fires_a_machine_updated_event_when_updating_a_machine()
    {
        $this->signInDeveloper();

        Event::fake([
            MachineUpdated::class,
        ]);

        $machine = create(Machine::class)->freshFromUuid();
        $attributes = array_filter(factory(Machine::class)->state('alternate')->make()->toArray());
        unset($attributes['uuid']);
        $machine->update($attributes);

        Event::assertDispatched(MachineUpdated::class, function ($event) use ($attributes, $machine) {
            $this->assertEquals($attributes, $event->attributes);
            $this->assertEquals($machine->uuid, $event->uuid);

            return true;
        });
    }


    /** @test */
    public function it_stores_a_machine_updated_event_when_updating_a_machine()
    {
        $user = $this->signInAdmin();

        $machine = create(Machine::class)->freshFromUuid();
        $attributes = collect(
            factory(Machine::class)->state('alternate')->make()->toArray()
        )->except([
            'uuid'
        ])->filter();
        $machine->update($attributes->toArray());

        $event = StoredEvent::where('event_class', MachineUpdated::class)->first();
        $this->assertEquals($machine->uuid, $event->event_properties['uuid']);
        $this->assertArraySubset($attributes->toArray(), $event->event_properties['attributes']);
        $this->assertEquals($user->id, $event->meta_data['user_id']);
        $this->assertArraySubset($attributes->toArray(), $machine->fresh()->toArray());
    }

    /** @test */
    public function it_fires_a_machine_deleted_event_when_deleting_a_machine()
    {
        $this->signInDeveloper();

        Event::fake([
            MachineDeleted::class,
        ]);

        $machine = create(Machine::class)->freshFromUuid();
        $machine->delete();

        Event::assertDispatched(MachineDeleted::class, function ($event) use ($machine) {
            $this->assertEquals($machine->uuid, $event->uuid);

            return true;
        });
    }

    /** @test */
    public function it_stores_a_machine_deleted_event_when_deleting_a_machine()
    {
        $user = $this->signInAdmin();

        $machine = create(Machine::class)->freshFromUuid();
        $machine->delete();

        $event = StoredEvent::where('event_class', MachineDeleted::class)->first();
        $this->assertEquals($machine->uuid, $event->event_properties['uuid']);
        $this->assertEquals($user->id, $event->meta_data['user_id']);
        $this->assertTrue($machine->fresh()->trashed());
    }

    /** @test */
    public function it_checks_wether_a_machine_is_on_a_board()
    {
        $machineOnBoard = create(Machine::class)->freshFromUuid();
        $pod = create(Pod::class);
        $board = create(Board::class);
        $pod->boards()->attach($board);
        $machineOnBoard->pods()->attach($pod);
        $this->assertTrue($machineOnBoard->freshFromUuid()->is_on_board);

        $machineOffBoard = create(Machine::class)->freshFromUuid();
        $this->assertFalse($machineOffBoard->freshFromUuid()->is_on_board);
    }
}
