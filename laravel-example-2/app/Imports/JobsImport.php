<?php

namespace App\Imports;

use App\Job;
use Throwable;
use App\Machine;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Domain\Supports\JobPlacement;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use App\Enums\JobFlag;

class JobsImport implements
    ToModel,
    WithChunkReading,
    WithValidation,
    WithHeadingRow,
    WithProgressBar,
    SkipsOnFailure,
    SkipsOnError,
    WithEvents
{
    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $row = collect($row)->filter()->map(function ($value) {
            return trim($value);
        });

        if ($this->isTotal($row)) {
            return null;
        }

        $job = Job::where('work_order_number', $row->get('work_numb'))->first();
        if (!$job) {
            $job = new Job;
            $attributes = $this->createAttributes($row);
            return $job->forceFill($attributes);
        }

        return $job->forceFill($this->updateAttributes($row));
    }

    protected function createAttributes(Collection $row)
    {
        return collect($this->updateAttributes($row))
            ->merge([
                'flag' => $this->getFlagFromDueAt($row->get('wo_cmplt_dt')),
            ])->toArray();
    }

    protected function updateAttributes(Collection $row)
    {
        return [
            'machine_id' => $this->getMachineId($row->get('mach_id')),
            'work_order_number' => $row->get('work_numb'),
            'control_number_1' => $row->get('cntrl_numb'),
            'screens_1' => $row->get('screens'),
            'placement_1' => $this->getJobPlacement($placement = $row->get('placement', '')),
            'imported_placement_1' => $placement,
            'control_number_2' => $row->get('cntrl_2'),
            'screens_2' => $row->get('screens_2'),
            'placement_2' => $this->getJobPlacement($placement_2 = $row->get('placement2', '')),
            'imported_placement_2' => $placement_2,
            'control_number_3' => $row->get('cntrl_3'),
            'screens_3' => $row->get('screens_3'),
            'placement_3' => $this->getJobPlacement($placement_3 = $row->get('placement3', '')),
            'imported_placement_3' => $placement_3,
            'control_number_4' => $row->get('cntrl_4'),
            'screens_4' => $row->get('screens_4'),
            'placement_4' => $this->getJobPlacement($placement_4 = $row->get('placement4', '')),
            'imported_placement_4' => $placement_4,
            'product_location_wc' => $row->get('prod_location_wc'),
            'wip_status' => $row->get('wip_stat'),
            'sku_number' => $row->get('sku_number'),
            'art_status' => $row->get('art_stat'),
            'priority' => $row->get('priority'),
            'pick_status' => $row->get('pick_stat'),
            'total_quantity' => $row->get('total_qty', 0),
            'small_quantity' => $row->get('s', 0),
            'medium_quantity' => $row->get('m', 0),
            'large_quantity' => $row->get('l', 0),
            'xlarge_quantity' => $row->get('xl', 0),
            '2xlarge_quantity' => $row->get('2xl', 0),
            'other_quantity' => $row->get('other', 0),
            'start_at' => $this->convertStartAt($row->get('wo_start_dt')),
            'due_at' => $this->convertDueAt($row->get('wo_cmplt_dt')),
        ];
    }

    protected function getFlagFromDueAt(?string $due_at)
    {
        if (!$due_at) {
            return;
        }

        $name = strtoupper($this->convertDateToCarbon($due_at)->dayName);
        if (!JobFlag::isValidName($name)) {
            return;
        }

        return JobFlag::keyForName($name);
    }

    public function chunkSize(): int
    {
        return 10;
    }

    public function rules(): array
    {
        return [
            'work_numb' => 'required',
        ];
    }

    public function onFailure(Failure ...$failures)
    {
        $failureMessage = r_collect($failures)->map->errors()->flatten()->implode(', ');
        Log::warning("Import validation error: {$failureMessage}");
    }

    public function onError(Throwable $e)
    {
        Log::warning('Import database error: ' . $e->getMessage());
    }

    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event) {
                Log::info('Import started: ' . now()->format('Y-m-d H:i:s'));
            },

            AfterImport::class => function (AfterImport $event) {
                Log::info('Import completed: ' . now()->format('Y-m-d H:i:s'));

                collect(Storage::disk('import')->allFiles())->each(function ($file) {
                    if ($file !== '.gitignore') {
                        Storage::disk('import')->delete($file);
                    }
                });
            },
        ];
    }

    protected function getJobPlacement(?string $imported_placement)
    {
        return (new JobPlacement)->convertFromImportValue($imported_placement);
    }

    protected function getMachineId(?string $external_machine_id)
    {
        if (!$external_machine_id) {
            return;
        }

        return optional(
            Machine::importFromExternalId($external_machine_id)
            )->id;
    }

    protected function convertStartAt(?string $start_at)
    {
        if (!$start_at) {
            return;
        }

        return $this->convertDateToCarbon($start_at)->startOfDay();
    }

    protected function convertDueAt(?string $due_at)
    {
        if (!$due_at) {
            return;
        }

        return $this->convertDateToCarbon($due_at)->endOfDay();
    }

    protected function convertDateToCarbon(string $date, $format = 'n/j/Y')
    {
        return Carbon::createFromFormat($format, $date);
    }

    protected function isTotal(Collection $row)
    {
        return strtolower($row->get('wo_start_dt')) === 'total';
    }
}
