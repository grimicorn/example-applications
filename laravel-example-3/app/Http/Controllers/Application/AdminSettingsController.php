<?php

namespace App\Http\Controllers\Application;

use App\Support\HasResponse;
use App\SystemMessage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminSettingsController extends Controller
{
    use HasResponse;

    public function index()
    {
        $system_message = SystemMessage::first();

        if ($system_message === null) {
            $system_message = new SystemMessage(['active' => false, 'message' => '', 'urgency' => 'alert']);
            $system_message->save();
            $system_message->fresh();
        }

        $urgency_options = ['Danger' => 'danger', 'Warning' => 'warning', 'Info' => 'info'];

        return view('app.sections.admin.admin-settings.index', [
            'pageTitle' => 'Admin',
            'pageSubtitle' => 'System Message',
            'section' => 'admin',
            'system_message' => $system_message,
            'urgency_options' => $urgency_options
        ]);
    }

//    public function update(Request $request, $id)
//    {
//        $message = SystemMessage::findOrFail($id);
//
//        $message->fill(array_map(function ($value) {
//            return ($value === '') ? null : $value;
//        }, $request->only($message->getFillable())));
//
//        $message->save();
//
//        return $this->successResponse('System Message saved successfully', $request);
//    }
}
