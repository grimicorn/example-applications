<?php

namespace App\Http\Controllers\Application;

use App\ExchangeSpace;
use Illuminate\Http\Request;
use App\Support\HasResponse;
use App\Http\Controllers\Controller;

class ExchangeSpaceTitleController extends Controller
{
    use HasResponse;

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'subtitle' => 'min:1|max:45|string|required',
        ]);

        $space = ExchangeSpace::findOrFail($id);
        $member = $space->currentMember;
        $member->custom_title = $request->get('subtitle');
        $member->save();

        return $this->successResponse('Exchange Space title updated successfully!', $request);
    }
}
