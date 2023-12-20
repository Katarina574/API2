<?php

namespace App\Exceptions;

class FileUploadException extends \Exception
{
    protected $message;
    public function __construct($message = "Greška prilikom čuvanja korisnika")
    {
        $this->message=$message;
    }
}
