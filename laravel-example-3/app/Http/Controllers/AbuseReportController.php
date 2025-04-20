<?php

namespace App\Http\Controllers;

use App\AbuseReportLink;
use App\Support\HasResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AbuseReported;

class AbuseReportController extends Controller
{
    use HasResponse;

    /**
     * Store a newly created resource in storage.
     *
     * @param int $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id)
    {
        $link = AbuseReportLink::findOrFail($id);
        $link->reported_on = now();
        $link->reason = request()->get('reason') ?? $link->reason;
        $link->reason_details = request()->get('reason_details') ?? $link->reason_details;
        $link->save();

        Mail::to('support@firmexchange.com')->send(new AbuseReported($link));

        return $this->successResponse(
            $status = 'Abuse report has been sent successfully.',
            request(),
            $route = $link->redirectUrl()
        );
    }
}
