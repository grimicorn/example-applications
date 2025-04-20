<?php

namespace App\Mail;

/**
 * Default email user for use when sending emails.
 */
class DefaultMailUser
{
    public $email;
    public $name;

    public function __construct()
    {
        $this->email = config('mail.to');
        $this->name = config('mail.name');
    }
}
