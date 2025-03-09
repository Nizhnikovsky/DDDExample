<?php

namespace App\Shared\Exception;

class TeamAmountExceededException extends \Exception implements ApiExceptionInterface
{
    public function __construct()
    {
        parent::__construct('Team amount exceeded. Only 11 allowed', 400);
    }

    public function getStatusCode()
    {
        return $this->getCode();
    }
}
