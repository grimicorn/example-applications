<?php

namespace App\Observers;

use App\Events\BroadcastingModelEvent;
use Illuminate\Database\Eloquent\Model;
use App\Events\BroadcastingModelDeletedEvent;

class BroadcastingModelObserver
{
    /**
     * Handle the model "created" event.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function created(Model $model)
    {
        event(new BroadcastingModelEvent($model, 'created'));
    }

    /**
     * Handle the model "updated" event.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function updated(Model $model)
    {
        event(new BroadcastingModelEvent($model, 'updated'));
    }

    /**
     * Handle the model "deleted" event.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function deleted(Model $model)
    {
        event(new BroadcastingModelDeletedEvent(
            $model->getKey(),
            class_basename($model),
            $model->user->id
        ));
    }

    /**
     * Handle the model "restored" event.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function restored(Model $model)
    {
        //
    }

    /**
     * Handle the model "force deleted" event.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function forceDeleted(Model $model)
    {
        //
    }
}
