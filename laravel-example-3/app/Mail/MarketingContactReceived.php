<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MarketingContactReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $fields;

    /**
     * Create a new message instance.
     *
     * @param $fields
     *
     * @return void
     */
    public function __construct($fields)
    {
        $this->fields = array_merge([
            'name' => '',
            'phone' => '',
            'email' => '',
            'preferred_contact_method' => '',
            'message' => '',
        ], $fields);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->fields['email'])
                    ->markdown('emails.marketing.contact-message')
                    ->subject('New Contact Form Notification');
    }
}
