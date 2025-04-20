<?php

namespace App\Http\Controllers\Application;

use App\Message;
use App\Conversation;
use App\Support\HasResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Support\ExchangeSpace\Attachments;

class BuyerInquiryConversationController extends Controller
{
    use HasResponse;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @param int $c_id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id, $c_id)
    {
        $request->validate([
            'body' => 'required',
            'files.new.*' => 'nullable|mimes:doc,docx,pdf,xls,xlsx,jpg,jpeg,bmp,png,pptx,ppt',
        ]);

        $conversation = Conversation::findOrFail($c_id);

        $message = new Message([
            'body' => $request->get('body'),
        ]);
        $message->user_id = Auth::id();
        $conversation->messages()->save($message);

        // Upload the files.
        (new Attachments($message->fresh()))->upload('files');

        return $this->successResponse(
            'Your message was added successfully',
            $request
        );
    }
}
