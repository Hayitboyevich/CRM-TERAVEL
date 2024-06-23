<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\MessageBag;
use Throwable;

class CustomExceptionHandler extends Exception
{
    private MessageBag $errors;

    public function __construct(MessageBag $errors, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
    }

    public function getErrors(): MessageBag
    {
        return $this->errors;
    }
}
