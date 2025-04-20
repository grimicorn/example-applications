<?php

namespace App\Domain\Concerns\Supports;

use Ramsey\Uuid\Uuid;
use Spatie\EventProjector\Facades\Projectionist;

trait StoresModelEvents
{
    public static function bootStoresModelEvents()
    {
        static::creating(function ($model) {
            if (! Projectionist::isProjecting()) {
                $model->storeCreatedEvent();

                return false;
            }
        });

        static::updating(function ($model) {
            if (! Projectionist::isProjecting()) {
                $model->storeUpdatedEvent();

                return false;
            }
        });

        static::deleting(function ($model) {
            if (! Projectionist::isProjecting()) {
                $model->storeDeletedEvent();

                return false;
            }
        });
    }

    public function storeCreatedEvent()
    {
        $createdEventClass = $this->getStoredModelEventClass('created');
        $this->uuid = (string) Uuid::uuid4();
        if (class_exists($createdEventClass)) {
            event(new $createdEventClass($this->attributes));
        }
    }

    public function storeUpdatedEvent()
    {
        $changedAttributes = array_filter($this->attributes, function ($attribute, string $key) {
            return ($this->original[$key] ?? null) !== $attribute;
        }, ARRAY_FILTER_USE_BOTH);
        if (count($changedAttributes)) {
            $updatedEventClass = $this->getStoredModelEventClass('updated');

            event(new $updatedEventClass($this->uuid, $changedAttributes));
        }
    }

    public function storeDeletedEvent()
    {
        $deletedEventClass = $this->getStoredModelEventClass('deleted');

        event(new $deletedEventClass($this->uuid));
    }

    protected function getStoredModelEventClass(string $event): string
    {
        return $this->storedModelEvents[$event] ?? '';
    }
}
