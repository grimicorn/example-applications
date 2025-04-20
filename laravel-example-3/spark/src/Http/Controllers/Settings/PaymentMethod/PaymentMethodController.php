<?php

namespace Laravel\Spark\Http\Controllers\Settings\PaymentMethod;

use Laravel\Spark\Spark;
use Laravel\Spark\Http\Controllers\Controller;
use Laravel\Spark\Contracts\Interactions\Settings\PaymentMethod\UpdatePaymentMethod;
use Laravel\Spark\Contracts\Http\Requests\Settings\PaymentMethod\UpdatePaymentMethodRequest;

class PaymentMethodController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Update the payment method for the user.
     *
     * @param  UpdatePaymentMethodRequest  $request
     * @return Response
     */
    public function update(UpdatePaymentMethodRequest $request)
    {
        $request->user()->forceFill([
            'card_expiration_month' => request()->get('card_expiration_month'),
            'card_expiration_year' => request()->get('card_expiration_year'),
            'card_name' => request()->get('card_name'),
        ])->save();

        Spark::interact(UpdatePaymentMethod::class, [
            $request->user(), $request->all(),
        ]);
    }
}
