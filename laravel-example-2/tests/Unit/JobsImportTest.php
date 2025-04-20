<?php

namespace Tests\Unit;

use App\Job;
use App\Machine;
use Carbon\Carbon;
use Tests\TestCase;
use App\Enums\ArtStatus;
use App\Enums\Placement;
use App\Enums\WipStatus;
use App\Enums\PickStatus;
use TiMacDonald\Log\LogFake;
use App\Enums\ProductLocationWc;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Enums\JobFlag;

class JobsImportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_new_job_from_import()
    {
        Artisan::call(
            'vms:import',
            [
                'path' => base_path('tests/fixtures/it_creates_a_new_job_from_import.xlsx'),
            ]
        );

        $this->assertCount(1, Job::all());

        $this->assertDatabaseHas('jobs', [
            'machine_id' => Machine::where('external_machine_id', 'A9')->first()->id,
            'work_order_number' => 'AW77581',
            'control_number_1' => '845112',
            'screens_1' => 4,
            'placement_1' => Placement::FRONT,
            'imported_placement_1' => 'FT -FRONT 2" DOWN FROM NECK SEAM',
            'control_number_2' => '1203166',
            'screens_2' => 2,
            'placement_2' => Placement::LEFT_LONG_SLEEVE,
            'imported_placement_2' => 'LLS -LEFT LONG SLEEVE',
            'control_number_3' => '1160580',
            'screens_3' => 6,
            'placement_3' => Placement::BACK,
            'imported_placement_3' => 'BK -BACK 2" DOWN FROM COLLAR',
            'control_number_4' => '1065617',
            'screens_4' => 1,
            'placement_4' => Placement::FOIL,
            'imported_placement_4' => 'CSTM-FOIL',
            'product_location_wc' => ProductLocationWc::P,
            'wip_status' => WipStatus::K,
            'sku_number' => 'TT06187BSCN857',
            'art_status' => ArtStatus::C,
            'priority' => 9,
            'pick_status' => PickStatus::H,
            'total_quantity' => 91,
            'small_quantity' => 20,
            'medium_quantity' => 11,
            'large_quantity' => 0,
            'xlarge_quantity' => 6,
            '2xlarge_quantity' => 4,
            'other_quantity' => 50,
            'start_at' => Carbon::createFromDate('2019', '3', '19')->startOfDay()->format('Y-m-d H:i:s'),
            'due_at' => Carbon::createFromDate('2019', '3', '26')->endOfDay()->format('Y-m-d H:i:s'),
            'flag' => JobFlag::TUESDAY,
        ]);
    }

    /** @test */
    public function it_updates_a_job_from_import()
    {
        $job = create(Job::class, [
            'work_order_number' => 'AW77581',
        ])->freshFromUuid();
        $original = $job;

        Artisan::call(
            'vms:import',
            [
                'path' => base_path('tests/fixtures/it_updates_a_job_from_import.xlsx'),
            ]
        );

        $this->assertCount(1, Job::all());

        $this->assertDatabaseMissing(
            'jobs',
            collect($job->toArray())->except($this->mergeWithJobAppends([
                'sort_order',
                'uuid',
                'customer_name',
                'work_order_number',
                'complete_count',
                'issue_count',
                'notes',
                'type',
                'duration',
                'started_at',
                'completed_at',
                'flag',
            ]))->toArray()
        );

        $this->assertDatabaseHas('jobs', [
            'machine_id' => Machine::where('external_machine_id', 'A9')->first()->id,
            'work_order_number' => 'AW77581',
            'control_number_1' => '845112',
            'screens_1' => 4,
            'placement_1' => Placement::FRONT,
            'imported_placement_1' => 'FT -FRONT 2" DOWN FROM NECK SEAM',
            'control_number_2' => '1203166',
            'screens_2' => 2,
            'placement_2' => Placement::LEFT_LONG_SLEEVE,
            'imported_placement_2' => 'LLS -LEFT LONG SLEEVE',
            'control_number_3' => '1160580',
            'screens_3' => 6,
            'placement_3' => Placement::BACK,
            'imported_placement_3' => 'BK -BACK 2" DOWN FROM COLLAR',
            'control_number_4' => '1065617',
            'screens_4' => 1,
            'placement_4' => Placement::FOIL,
            'imported_placement_4' => 'CSTM-FOIL',
            'product_location_wc' => ProductLocationWc::P,
            'wip_status' => WipStatus::K,
            'sku_number' => 'TT06187BSCN857',
            'art_status' => ArtStatus::C,
            'priority' => 9,
            'pick_status' => PickStatus::H,
            'total_quantity' => 91,
            'small_quantity' => 20,
            'medium_quantity' => 11,
            'large_quantity' => 0,
            'xlarge_quantity' => 6,
            '2xlarge_quantity' => 4,
            'other_quantity' => 50,
            'start_at' => Carbon::createFromDate('2019', '3', '19')->startOfDay()->format('Y-m-d H:i:s'),
            'due_at' => Carbon::createFromDate('2019', '3', '26')->endOfDay()->format('Y-m-d H:i:s'),
            'flag' => $job->flag,
        ]);
    }

    /** @test */
    public function it_imports_external_files()
    {
        Storage::fake();
        $filename = 'it_imports_external_files.xlsx';

        if (!file_exists($link = public_path($filename))) {
            symlink(base_path("tests/fixtures/{$filename}"), $link);
        }

        $this->assertCount(0, Job::all());


        Artisan::call(
            'vms:import',
            [
                'path' => url($filename),
            ]
        );

        $this->assertCount(1, Job::all());


        unlink($link);
    }

    /** @test */
    public function it_deletes_the_import_file_once_completed()
    {
        Storage::fake();

        $filename = 'it_creates_a_new_job_from_import.xlsx';
        $fixture = base_path("tests/fixtures/{$filename}");
        if (!Storage::disk('import')->exists($filename)) {
            Storage::disk('import')->put($filename, file_get_contents($fixture));
        }

        $this->assertTrue(Storage::disk('import')->exists($filename));
        $this->assertCount(0, Job::all());


        Artisan::call(
            'vms:import',
            [
                'path' => $filename,
            ]
        );

        $this->assertCount(1, Job::all());
        $this->assertFalse(Storage::disk('import')->exists($filename));
    }

    /** @test */
    public function it_logs_database_errors_from_import()
    {
        Log::swap(new LogFake);

        Artisan::call(
            'vms:import',
            [
                'path' => base_path('tests/fixtures/it_logs_database_errors_from_import.xlsx'),
            ]
        );

        $this->assertCount(0, Job::all());
        Log::assertLogged('warning', function ($message, $context) {
            return str_contains($message, 'Import database error:');
        });
    }

    /** @test */
    public function it_logs_validation_errors_from_import()
    {
        Log::swap(new LogFake);

        Artisan::call(
            'vms:import',
            [
                'path' => base_path('tests/fixtures/it_logs_validation_errors_from_import.xlsx'),
            ]
        );

        $this->assertCount(0, Job::all());
        Log::assertLogged('warning', function ($message, $context) {
            return str_contains($message, 'Import validation error:');
        });
    }
}
