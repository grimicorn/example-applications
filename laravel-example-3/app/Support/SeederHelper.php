<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;

trait SeederHelper
{
    /**
     * Adds a photo.
     *
     * @param Illuminate\Database\Eloquent\Model $model
     * @param string $filename
     * @param string $collection
     * @return void
     */
    protected function addPhoto($model, $filename, $collection = 'default')
    {
        if (is_null($filename)) {
            return;
        }

        $path = base_path("tests/mocks/files/{$filename}");
        if (file_exists($path)) {
            $model->addMedia($path)
                    ->preservingOriginal()
                    ->toMediaCollection($collection);
        }
    }

    /**
     * Remvoes non-fillable
     *
     * @param Model $model
     * @return void
     */
    public function removeNonFillableToArray(Model $model)
    {
        $fillable = $model->getFillable();

        return collect($model->toArray())
        ->filter(function ($value, $key) use ($fillable) {
            return in_array($key, $fillable);
        })->toArray();
    }
}
