<?php


namespace App\Shared\Exception;

class ValueValidationException extends \Exception implements ApiExceptionInterface
{
    public function __construct(string $message = "")
    {
        parent::__construct($message, 422);
    }

    public function getStatusCode()
    {
        return $this->getCode();
    }
}