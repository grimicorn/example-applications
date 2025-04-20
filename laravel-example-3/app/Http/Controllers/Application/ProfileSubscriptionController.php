<?php

namespace App\Http\Controllers\Application;

use Illuminate\Http\Request;
use App\Support\HasStripeHelpers;
use App\Http\Controllers\Controller;

class ProfileSubscriptionController extends Controller
{
    use HasStripeHelpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->check()) {
            abort(403, 'Forbidden');
        }

        return $this->getStripePricing();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        if (!auth()->check()) {
            abort(403, 'Forbidden');
        }

        $user = auth()->user();
        return [
            'status' => $user->account_status,
            'subscription' => $user->currentSubscription(),
        ];
    }
}
