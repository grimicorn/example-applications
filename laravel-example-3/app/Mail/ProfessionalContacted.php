<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProfessionalContacted extends Mailable
{
    use Queueable, SerializesModels;

    public $fields;
    public $professional;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($fields, $professional)
    {
        $this->fields = $fields;
        $this->professional = $professional;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.marketing.professional-contact-message')
        ->subject("Firm Exchange – New message from a user");
    }
}
