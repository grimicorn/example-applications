<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\MailableTestSend;
use Illuminate\Support\Facades\Mail;

class MailableTestSendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $email = request()->get('email', 'dholloran@matchboxdesigngroup.com');

        Mail::to($email)->send(new MailableTestSend());

        return "Test sent to {$email}";
    }
}
