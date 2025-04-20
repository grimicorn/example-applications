<?php

namespace App\Domain\Concerns;

trait SyncsPolymorphicRelationships
{
    public function syncPolymorphicRelationship(array $ids, string $relationship, string $model)
    {
        $submittedIds = collect($ids)->unique();
        $currentIds = $this->$relationship->pluck('id');
        $newIds = $submittedIds->unique()->reject(function ($id) use ($currentIds) {
            return $currentIds->contains($id);
        });

        // Remove old relationship
        $deleteIds = $currentIds->reject(function ($id) use ($submittedIds) {
            return $submittedIds->contains($id);
        });
        $this->$relationship()->whereIn("{$relationship}.id", $deleteIds)->get()
            ->each(fn ($item) => $item->pivot->delete());

        // Add new relationship
        $this->$relationship()->saveMany($model::whereIn(
            'id',
            collect($newIds)->toArray(),
        )->get());

        return $this;
    }
}
