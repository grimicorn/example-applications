<?php

namespace App\Http\Controllers\Application;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Support\HasResponse;
use App\Message;

class MessageController extends Controller
{
    use HasResponse;

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $message = Message::findOrFail($id);

        // Set the deleted by id so we have a tracker
        $message->deleted_by_id = auth()->id();
        $message->save();

        // Delete the message
        $message->delete();

        return $this->successResponse('Message deleted successfully.');
    }
}
