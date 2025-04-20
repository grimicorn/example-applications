<?php

namespace App\Http\Controllers\Application;

use App\ExchangeSpace;
use App\RejectionReason;
use Illuminate\Http\Request;
use App\Support\HasResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Support\ExchangeSpaceDealType;
use App\Support\ExchangeSpaceStatusType;
use App\Support\ExchangeSpace\MemberRole;
use App\Support\Notification\HasNotifications;

class BuyerInquiryAcceptanceController extends Controller
{
    use HasResponse;
    use HasNotifications;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        // Accept the inquiry.
        $space = ExchangeSpace::findOrFail($id)->acceptInquiry();

        return $this->successResponse(
            'Business inquiry accepted successfully!',
            $request,
            route('exchange-spaces.conversations.show', [
                'id' => $space->id,
                'c_id' => $space->inquiry_conversation->id,
            ])
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $request->validate(['reason' => 'required']);

        // Reject the inquiry and the delete the space
        $inquiry = ExchangeSpace::findOrFail($id);
        $inquiry->delete();

        return $this->successResponse(
            'Business inquiry rejected successfully!',
            $request,
            route('business-inquiry.index')
        );
    }
}
