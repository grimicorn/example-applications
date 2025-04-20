<?php

namespace App\Http\Controllers\Marketing;

use App\User;
use Illuminate\Http\Request;
use App\Mail\ProfessionalContacted;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Support\Notification\HasNotifications;
use App\Support\Notification\ProfessionalContactedNotification;

class ProfessionalContactFormController extends Controller
{
    use HasNotifications;

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // This will allow the user to login to be able to access the contact form
        if (Auth::guest()) {
            return redirect()->guest('login');
        }

        // If the user has logged in they will be redirected
        // back here so lets forward them to the
        // intended professionals page.
        return redirect(route('professional.show', ['id' => $id]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        session()->flash('contact_form_inputs', $request->all());
        // Validate
        $this->validate($request, [
            'email' => 'required_without:phone|email',
            'name' => 'required|string', // Required
            'phone' => 'required_without:email',
            'message' => 'required|string', // Required
        ]);

        $this->dispatchNotification(new ProfessionalContactedNotification(
            $recipient = User::findOrFail($id),
            $fields = $request->all('email', 'name', 'phone', 'message')
        ));

        return back()->with('status', 'Your email has been successfully sent!')
                            ->with('success', true);
    }
}
