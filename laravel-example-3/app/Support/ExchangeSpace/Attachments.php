<?php

namespace App\Support\ExchangeSpace;

use App\Message;
use App\Support\HasForms;
use Illuminate\Support\Facades\Auth;

class Attachments
{
    use HasForms;

    protected $space;

    public function __construct(Message $message)
    {
        $this->message = $message;
        $this->conversation = $message->conversation;
        $this->space = $this->conversation->space;
    }

    public function upload($collection)
    {
        $files = request()->file($collection);
        $files = isset($files['new']) ? $files['new'] : [];

        foreach ($files as $key => $file) {
            $this->space
            ->addMediaFromRequest("{$collection}.new.{$key}")
            ->withCustomProperties([
                'message_id' => $this->message->id,
                'conversation_id' => $this->conversation->id,
                'user_id' => Auth::id(),
            ])
            ->toMediaCollection();
        }
    }
}
