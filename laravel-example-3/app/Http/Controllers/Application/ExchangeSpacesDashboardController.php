<?php

namespace App\Http\Controllers\Application;

use App\ExchangeSpace;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExchangeSpacesDashboardController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $member = $this->getMemberFromSpaceId($id);
        $member->dashboard = true;
        $member->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $member = $this->getMemberFromSpaceId($id);
        $member->dashboard = false;
        $member->save();
    }

    protected function getMemberFromSpaceId($id)
    {
        $space = ExchangeSpace::findOrFail($id);
        $member = $space->members()->ofCurrentUser()->get()->first();

        if (is_null($member)) {
            abort(403, 'Forbidden.');
        }

        return $member;
    }
}
