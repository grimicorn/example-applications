<?php

namespace App\Http\Controllers\Marketing;

use App\User;
use Illuminate\Http\Request;
use App\Support\ContactInformation;
use App\Http\Controllers\Controller;
use App\MarketingContactNotification;
use App\Mail\MarketingContactReceived;
use Illuminate\Support\Facades\Validator;

class ContactFormController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'preferred_contact_method' => 'required',
            'message' => 'required',
            'g-recaptcha-response' => app()->environment('testing') ? 'nullable' : 'recaptcha|required',
        ])->validate();

        $fields = $request->all([
            'name',
            'phone',
            'email',
            'preferred_contact_method',
            'message',
        ]);

        \Mail::to(ContactInformation::getEmail('support'))
            ->send(new MarketingContactReceived($fields));

        (new MarketingContactNotification($fields))->save();

        return back()->with('status', 'Your message has been sent and we will be in touch soon!')
                     ->with('success', true);
    }
}
