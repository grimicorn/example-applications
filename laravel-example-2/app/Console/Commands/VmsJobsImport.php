<?php

namespace App\Console\Commands;

use App\Imports\JobsImport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class VmsJobsImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vms:import {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs the FMS -> VMS job import';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $path = $this->argument('path');
        $filename = pathinfo($path, PATHINFO_BASENAME);
        if (!Storage::disk('import')->exists($filename)) {
            Storage::disk('import')->put($filename, file_get_contents($path));
        }

        (new JobsImport)->withOutput($this->output)
            ->import($filename, 'import');
    }
}
