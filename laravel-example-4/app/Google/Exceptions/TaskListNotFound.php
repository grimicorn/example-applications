<?php

namespace App\Google\Exceptions;

use Exception;

class TaskListNotFound extends Exception
{

    function __construct($message = null, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->message = is_null($message) ? 'Task list not found' : $message;
    }
}
