<?php

namespace App\Support;

use App\Snapshot;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class SnapshotDifference
{
    protected $snapshot;
    protected $baseline;

    public function __construct(Snapshot $snapshot)
    {
        $this->snapshot = $snapshot;
        $this->snapshotMedia = $this->snapshot->getFirstMedia('snapshot');
        $this->baselineMedia = $this->snapshot->configuration->getBaselineMedia();
    }

    public function calculate()
    {
        $difference = null;

        if (!$this->baselineMedia or !$this->snapshotMedia) {
            return $difference;
        }

        if ($this->snapshot->is_baseline) {
            return $difference;
        }

        $difference = $this->process();

        $this->storeDifferenceImage();

        return $difference;
    }

    protected function formatDifference($difference)
    {
        if (is_null($difference) or floatval($difference) <= 0) {
            return $difference;
        }

        return $difference / 100;
    }

    protected function process()
    {
        $process = new Process([
            'node',
            resource_path('js/node/pixelmatch.js'),
            $this->baselineMedia->getFullUrl(),
            $this->snapshotMedia->getFullUrl(),
            $this->diffPath(),
        ]);

        $process->run();

        if (!$process->isSuccessful()) {
            return;
        }

        $output = optional(
            json_decode(trim($process->getOutput()))
        );

        if ($output->success and !is_null($output->difference)) {
            return $this->formatDifference(
                $output->difference
            );
        }
    }

    protected function storeDifferenceImage()
    {
        if (!File::exists($this->diffPath())) {
            return;
        }

        $this->snapshot
            ->addMedia($this->diffPath())
            ->toMediaCollection('difference');
    }

    protected function diffPath()
    {
        $path = $this->snapshotMedia->getPath();
        $filename = pathinfo($path, PATHINFO_FILENAME);
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        return storage_path(
            "app/temp/{$filename}-diff.{$extension}"
        );
    }
}
