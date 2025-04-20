<?php

use App\Job;
use App\Machine;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Enums\JobFlag;
use App\Enums\JobType;
use App\Enums\ArtStatus;
use App\Enums\WipStatus;
use App\Enums\PickStatus;
use Faker\Generator as Faker;
use App\Enums\ProductLocationWc;
use App\Domain\Database\SeederHelper;
use App\Enums\Placement;

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

$factory->define(Job::class, function (Faker $faker) {
    return [
        'machine_id' => function () {
            return (new SeederHelper)->getRandomMachine()->id;
        },
        'uuid' => (string) Uuid::uuid4(),
        'customer_name' => 'Customer One',
        'work_order_number' => 'WO1',
        'control_number_1' => '1000001',
        'screens_1' => 1,
        'placement_1' => Placement::RIGHT_CHEST,
        'imported_placement_1' => Placement::RIGHT_CHEST,
        'control_number_2' => '1000002',
        'screens_2' => 2,
        'placement_2' => Placement::LEFT_CHEST,
        'imported_placement_2' => Placement::LEFT_CHEST,
        'control_number_3' => '1000003',
        'screens_3' => 3,
        'placement_3' => Placement::BACK,
        'imported_placement_3' => Placement::BACK,
        'control_number_4' => '1000004',
        'screens_4' => 4,
        'placement_4' => Placement::FRONT,
        'imported_placement_4' => Placement::FRONT,
        'product_location_wc' => 'P',
        'wip_status' => 'K',
        'sku_number' => '123456',
        'art_status' => 'C',
        'priority' => 9,
        'pick_status' => 'H',
        'total_quantity' => 114,
        'small_quantity' => 18,
        'medium_quantity' => 22,
        'large_quantity' => 34,
        'xlarge_quantity' => 24,
        '2xlarge_quantity' => 16,
        'other_quantity' => 0,
        'complete_count' => 22,
        'issue_count' => 3,
        'notes' => $faker->realText(),
        'type' => JobType::DEFAULT,
        'duration' => null,
        'sort_order' => 1,
        'start_at' => Carbon::createFromDate(2019, 4, 5)->endOfDay(),
        'due_at' => Carbon::createFromDate(2019, 4, 26)->endOfDay(),
        'started_at' => Carbon::createFromDate(2019, 4, 5),
        'completed_at' => Carbon::createFromDate(2019, 4, 25),
        'garment_ready' => false,
        'screens_ready' => false,
        'ink_ready' => false,
    ];
});



$factory->state(Job::class, 'only-updatable', function (Faker $faker) {
    return [
        'work_order_number' => null,
        'uuid' => null,
        'customer_name' => null,
        'control_number_1' => null,
        'screens_1' => null,
        'placement_1' => null,
        'imported_placement_1' => null,
        'control_number_2' => null,
        'screens_2' => null,
        'placement_2' => null,
        'imported_placement_2' => null,
        'control_number_3' => null,
        'screens_3' => null,
        'placement_3' => null,
        'imported_placement_3' => null,
        'control_number_4' => null,
        'screens_4' => null,
        'placement_4' => null,
        'imported_placement_4' => null,
        'product_location_wc' => null,
        'sku_number' => null,
        'total_quantity' => null,
        'small_quantity' => null,
        'medium_quantity' => null,
        'large_quantity' => null,
        'xlarge_quantity' => null,
        '2xlarge_quantity' => null,
        'other_quantity' => null,
        'complete_count' => null,
        'issue_count' => null,
        'start_at' => Carbon::createFromDate(2019, 4, 5)->endOfDay(),
        'due_at' => Carbon::createFromDate(2019, 4, 26)->endOfDay(),
        'flag' => JobFlag::FRIDAY,
        'garment_ready' => false,
        'screens_ready' => false,
        'ink_ready' => false,
    ];
});


$factory->state(Job::class, 'alternate', function ($faker) {
    return [
        'machine_id' => function () {
            create(Machine::class)->freshFromUuid()->id;
        },
        'customer_name' => 'Customer Two',
        'control_number_1' => '2000001',
        'screens_1' => 4,
        'placement_1' => Placement::FRONT,
        'imported_placement_1' => Placement::FRONT,
        'control_number_2' => '2000002',
        'screens_2' => 3,
        'placement_2' => Placement::BACK,
        'imported_placement_2' => Placement::BACK,
        'control_number_3' => '2000003',
        'screens_3' => 2,
        'placement_3' => Placement::RIGHT_CHEST,
        'imported_placement_3' => Placement::RIGHT_CHEST,
        'control_number_4' => '2000004',
        'screens_4' => 1,
        'placement_4' => Placement::LEFT_CHEST,
        'imported_placement_4' => Placement::LEFT_CHEST,
        'product_location_wc' => 'M', // This is not real (provided data only has P)
        'wip_status' => 'J',
        'sku_number' => '654321',
        'art_status' => 'A',
        'priority' => 7,
        'pick_status' => 'N',
        'total_quantity' => 205,
        'small_quantity' => 17,
        'medium_quantity' => 34,
        'large_quantity' => 62,
        'xlarge_quantity' => 77,
        '2xlarge_quantity' => 15,
        'other_quantity' => 22,
        'complete_count' => 12,
        'issue_count' => 0,
        'notes' => $faker->realText(),
        'type' => JobType::DEFAULT,
        'sort_order' => 2,
        'start_at' => Carbon::createFromDate(2019, 3, 20)->startOfDay(),
        'due_at' => Carbon::createFromDate(2019, 3, 22)->endOfDay(),
        'started_at' => Carbon::createFromDate(2019, 3, 20),
        'completed_at' => Carbon::createFromDate(2019, 3, 22),
        'garment_ready' => true,
        'screens_ready' => true,
        'ink_ready' => true,
    ];
});

$factory->state(Job::class, 'alternate-only-updatable', function ($faker) {
    return [
        'machine_id' => function () {
            create(Machine::class)->freshFromUuid()->id;
        },
        'customer_name' => null,
        'work_order_number' => null,
        'uuid' => null,
        'control_number_1' => null,
        'screens_1' => null,
        'placement_1' => null,
        'imported_placement_1' => null,
        'control_number_2' => null,
        'screens_2' => null,
        'placement_2' => null,
        'imported_placement_2' => null,
        'control_number_3' => null,
        'screens_3' => null,
        'placement_3' => null,
        'imported_placement_3' => null,
        'control_number_4' => null,
        'screens_4' => null,
        'placement_4' => null,
        'imported_placement_4' => null,
        'product_location_wc' => null,
        'wip_status' => 'J',
        'sku_number' => null,
        'art_status' => 'A',
        'priority' => 7,
        'pick_status' => 'N',
        'total_quantity' => null,
        'small_quantity' => null,
        'medium_quantity' => null,
        'large_quantity' => null,
        'xlarge_quantity' => null,
        '2xlarge_quantity' => null,
        'other_quantity' => null,
        'complete_count' => null,
        'issue_count' => null,
        'notes' => $faker->realText(),
        'type' => JobType::DEFAULT,
        'sort_order' => 2,
        'start_at' => Carbon::createFromDate(2019, 3, 19)->startOfDay(),
        'due_at' => Carbon::createFromDate(2019, 3, 24)->endOfDay(),
        'started_at' => Carbon::createFromDate(2019, 3, 19),
        'completed_at' => Carbon::createFromDate(2019, 3, 24),
        'flag' => JobFlag::WEDNESDAY,
        'garment_ready' => true,
        'screens_ready' => true,
        'ink_ready' => true,
    ];
});


$factory->state(Job::class, 'specialty', function ($faker) {
    return [
        'work_order_number' => null,
        'customer_name' => null,
        'uuid' => (string) Uuid::uuid4(),
        'control_number_1' => null,
        'screens_1' => null,
        'placement_1' => null,
        'imported_placement_1' => null,
        'control_number_2' => null,
        'screens_2' => null,
        'placement_2' => null,
        'imported_placement_2' => null,
        'control_number_3' => null,
        'screens_3' => null,
        'placement_3' => null,
        'imported_placement_3' => null,
        'control_number_4' => null,
        'screens_4' => null,
        'placement_4' => null,
        'imported_placement_4' => null,
        'product_location_wc' => null,
        'wip_status' => null,
        'sku_number' => null,
        'art_status' => null,
        'priority' => null,
        'pick_status' => null,
        'total_quantity' => null,
        'small_quantity' => null,
        'medium_quantity' => null,
        'large_quantity' => null,
        'xlarge_quantity' => null,
        '2xlarge_quantity' => null,
        'other_quantity' => null,
        'complete_count' => null,
        'issue_count' => null,
        'notes' => null,
        'duration' => random_int(0, 180), // 0 - 3hrs in minutes
        'type' => collect(JobType::keys())->filter(function ($key) {
            return $key !== JobType::DEFAULT;
        })->random(),
        'start_at' => null,
        'due_at' => null,
        'started_at' => null,
        'completed_at' => null,
        'flag' => null,
        'garment_ready' => false,
        'screens_ready' => false,
        'ink_ready' => false,
    ];
});

$factory->state(Job::class, 'random', function ($faker) {
    return [
        'machine_id' => function () {
            return factory(Machine::class)->create()->freshFromUuid();
        },
        'customer_name' => $faker->sentence(),
        'uuid' => (string) Uuid::uuid4(),
        'work_order_number' => strtoupper($faker->word()),
        'control_number_1' => strtoupper($faker->word()),
        'screens_1' => random_int(1, 10),
        'placement_1' => collect(Placement::keys())->random(),
        'imported_placement_1' => collect(Placement::keys())->random(),
        'control_number_2' => strtoupper($faker->word()),
        'screens_2' => random_int(1, 10),
        'placement_2' => collect(Placement::keys())->random(),
        'imported_placement_2' => collect(Placement::keys())->random(),
        'control_number_3' => strtoupper($faker->word()),
        'screens_3' => random_int(1, 10),
        'placement_3' => collect(Placement::keys())->random(),
        'imported_placement_3' => collect(Placement::keys())->random(),
        'control_number_4' => strtoupper($faker->word()),
        'screens_4' => random_int(1, 10),
        'placement_4' => collect(Placement::keys())->random(),
        'imported_placement_4' => collect(Placement::keys())->random(),
        'product_location_wc' => collect(ProductLocationWc::keys())->random(),
        'wip_status' => collect(WipStatus::keys())->random(),
        'sku_number' => strtoupper($faker->word()),
        'art_status' => collect(ArtStatus::keys())->random(),
        'priority' => random_int(1, 10),
        'pick_status' => collect(PickStatus::keys())->random(),
        'total_quantity' => random_int(0, 500),
        'small_quantity' => random_int(0, 500),
        'medium_quantity' => random_int(0, 500),
        'large_quantity' => random_int(0, 500),
        'xlarge_quantity' => random_int(0, 500),
        '2xlarge_quantity' => random_int(0, 500),
        'other_quantity' => random_int(0, 500),
        'complete_count' => random_int(0, 500),
        'issue_count' => random_int(0, 500),
        'notes' => $faker->realText(),
        'duration' => random_int(0, 12 * 60),
        'sort_order' => random_int(0, 10),
        'start_at' => Carbon::createFromDate(2019, 4, 5)->startOfDay(),
        'due_at' => Carbon::createFromDate(2019, 4, 26)->endOfDay(),
        'started_at' => Carbon::createFromDate(2019, 4, 5),
        'completed_at' => Carbon::createFromDate(2019, 4, 25),
        'garment_ready' => $faker->boolean(),
        'screens_ready' => $faker->boolean(),
        'ink_ready' => $faker->boolean(),
    ];
});
