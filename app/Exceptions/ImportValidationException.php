<?php

namespace App\Exceptions;

use Exception;

class ImportValidationException extends Exception
{
    protected $errors;

    public function __construct($errors)
    {
        parent::__construct("Validation failed during import.");
        $this->errors = $errors;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
