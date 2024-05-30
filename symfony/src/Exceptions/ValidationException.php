<?php

namespace App\Exceptions;

use Exception;

class ValidationException extends Exception
{
    private array $errors;

    public function __construct(array $errors = [], int $code = 422)
    {
        $this->errors = $errors;
        parent::__construct($code);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}