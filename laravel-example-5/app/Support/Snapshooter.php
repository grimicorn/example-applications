<?php

namespace App\Support;

use App\Snapshot;
use Illuminate\Support\Str;
use App\Jobs\CleanUpSnapshots;
use App\SnapshotConfiguration;
use Spatie\Image\Manipulations;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Snapshooter
{
    protected $page;
    protected $configuration;
    protected $fileName;

    public function __construct(SnapshotConfiguration $configuration)
    {
        $this->configuration = $configuration;
        $this->page = $configuration->page;
        $this->fileName = $this->newFileName();
    }

    public function take()
    {
        if ($this->page->ignored) {
            return;
        }

        $this->browsershot();
        $this->moveTempFileToStorage(
            $snapshot = $this->saveSnapshot()
        );

        $snapshot->fresh()->calculateDifference();

        $this->configuration->setNeedsReviewStatus();

        CleanUpSnapshots::dispatch($this->configuration->fresh())
            ->onQueue('snapshots-clean');

        return $snapshot->fresh();
    }

    public function fileName()
    {
        return $this->fileName;
    }

    protected function path($file_name = '')
    {
        return collect([
            $this->directory(),
            $file_name
        ])->filter()->implode('/');
    }

    protected function saveSnapshot()
    {
        $snapshot = new Snapshot;
        $snapshot->fill([
            'snapshot_configuration_id' => $this->configuration->id,
            'is_baseline' => $this->configuration->snapshots()->baseline()->get()->isEmpty(),
        ]);

        $snapshot->save();

        return $snapshot;
    }

    protected function newFileName()
    {
        return sha1($this->page->id . time() . Str::uuid()) . '.png';
    }

    protected function browsershot()
    {
        Browsershot::url($this->page->url)
            ->setOption('viewport.width', $this->configuration->width)
            ->fullPage()
            ->ignoreHttpsErrors()
            ->dismissDialogs()
            ->setDelay(4000)
            ->waitUntilNetworkIdle()
            ->save($this->tempPath());

        return $this;
    }

    protected function moveTempFileToStorage(Snapshot $snapshot)
    {
        $snapshot
            ->addMedia($this->tempPath())
            ->toMediaCollection('snapshot');

        return $this;
    }

    protected function tempPath()
    {
        return storage_path("app/temp/{$this->fileName}");
    }

    protected function directory()
    {
        $directory = collect([
            'public',
            $this->page->site->user->id,
            'snapshots',
            $this->page->id,
        ])->implode('/');

        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }

        return $directory;
    }
}
