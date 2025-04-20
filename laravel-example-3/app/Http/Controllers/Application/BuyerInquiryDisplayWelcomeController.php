<?php

namespace App\Http\Controllers\Application;

use Illuminate\Http\Request;
use App\Support\HasResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BuyerInquiryDisplayWelcomeController extends Controller
{
    use HasResponse;

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // Set the property on the user since it will be the same message for all buyer inquires.
        $user = Auth::user();
        $user->display_inquiry_intro = false;
        $user->save();


        return $this->successResponse('Introduction Pop up disabled succuessfully!', $request);
    }
}
