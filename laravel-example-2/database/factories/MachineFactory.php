<?php

use App\Machine;
use Ramsey\Uuid\Uuid;
use App\Enums\MachineStatus;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Machine::class, function (Faker $faker) {
    return [
        'uuid' => (string) Uuid::uuid4(),
        'external_machine_id' => $faker->word(),
        'name' => $faker->sentence(),
        'description' => $faker->sentences(3, true),
        'status' => MachineStatus::RUNNING,
        'has_auto_unloader' => false,
    ];
});

$factory->state(Machine::class, 'alternate', function (Faker $faker) {
    return [
        'uuid' => (string) Uuid::uuid4(),
        'external_machine_id' => $faker->word(),
        'name' => $faker->sentence(),
        'description' => $faker->sentences(3, true),
        'status' => MachineStatus::DOWN,
        'has_auto_unloader' => true,
    ];
});
