<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BroadcastingModelEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The name of the queue on which to place the event.
     *
     * @var string
     */
    public $broadcastQueue = 'broadcast';

    public $model;
    public $modelKey;
    public $modelType;
    public $eventType;
    protected $userId;
    protected $originalModel;

    public function __construct(Model $model, $eventType)
    {
        $this->model = $model->toArray();
        $this->modelKey = $model->getKey();
        $this->modelType = class_basename($model);
        $this->eventType = $eventType;
        $this->originalModel = $model;

        if ($this->modelType === 'User') {
            $this->userId = $model->getKey();
        } else {
            $this->userId = optional($model->user)->getKey();
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel("user.{$this->userId}");
    }
}
