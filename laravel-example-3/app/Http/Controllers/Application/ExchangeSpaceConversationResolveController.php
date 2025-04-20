<?php

namespace App\Http\Controllers\Application;

use App\Conversation;
use Illuminate\Http\Request;
use App\Support\HasResponse;
use App\Http\Controllers\Controller;

class ExchangeSpaceConversationResolveController extends Controller
{
    use HasResponse;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $c_id)
    {
        $conversation = Conversation::findOrFail($c_id);
        $conversation->resolved = true;
        $conversation->save();

        return $this->successResponse(
            "{$conversation->title} resolved successfully!",
            $request
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $c_id)
    {
        $conversation = Conversation::findOrFail($c_id);

        if (!$conversation->isReadonly()) {
            $conversation->resolved = false;
            $conversation->save();

            return $this->successResponse(
                "{$conversation->title} unresolved successfully!",
                $request
            );
        }

        return $this->successResponse(
            "This conversation can not be unresolved.",
            $request
        );
    }
}
