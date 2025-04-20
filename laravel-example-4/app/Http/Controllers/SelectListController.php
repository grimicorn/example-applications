<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SelectListController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'selected_list' => 'required|filled',
        ]);

        $user = \Auth::User();
        $user->selected_list = $request->input('selected_list');
        $user->save();

        return back()->with('alert_success', 'List selected successfully!');
    }
}
