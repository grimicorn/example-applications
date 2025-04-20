<?php

namespace App\Jobs\Fix;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SaveModels implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $models;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($models)
    {
        $this->models = $models;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->models->each(function ($model) {
            if (method_exists($model, 'disableCache')) {
                $model->disableCache()->save();
            } else {
                $model->save();
            }
        });
    }
}
