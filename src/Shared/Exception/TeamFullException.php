<?php

namespace App\Shared\Exception;

class TeamFullException extends \Exception implements ApiExceptionInterface
{
    public function __construct(string $teamName)
    {
        parent::__construct(sprintf('%s team is already has full line-up', $teamName), 422);
    }

    public function getStatusCode()
    {
        return $this->getCode();
    }
}
