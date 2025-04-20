<?php

namespace App\Http\Controllers\Application;

use App\ExchangeSpace;
use Illuminate\Http\Request;
use App\Support\HasResponse;
use App\Http\Controllers\Controller;
use App\Support\ExchangeSpaceDealType;

class ExchangeSpaceAdvanceStage extends Controller
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
        $space = ExchangeSpace::findOrFail($id);

        if ($space->deal_complete) {
            return $this->successResponse('Exchange Space is already complete.', $request);
        }

        $space->advanceStage();

        return $this->successResponse(
            'Exchange Space stage advanced successfully.',
            $request,
            null,
            ['stage_advanced_to' => $space->deal]
        );
    }
}
