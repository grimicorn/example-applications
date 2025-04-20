<?php

namespace App\Http\Controllers\Application;

use App\Support\HasResponse;
use App\SystemMessage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SystemMessageController extends Controller
{
    use HasResponse;

    public function update(Request $request, $id)
    {
        $message = SystemMessage::findOrFail($id);

        $message->fill(array_map(function ($value) {
            return ($value === '') ? null : $value;
        }, $request->only($message->getFillable())));

        $message->save();

        return $this->successResponse('System Message saved successfully', $request);
    }
}
