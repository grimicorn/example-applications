<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\AbuseReportLink;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AbuseReported extends Mailable
{
    use Queueable, SerializesModels;

    public $link;

    public function __construct(AbuseReportLink $link)
    {
        $this->link = $link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->link->reporter->email)
        ->subject('Firm Exchange - New Abuse Report')
        ->markdown('email.abuse-report');
    }
}
