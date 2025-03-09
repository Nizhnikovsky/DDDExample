<?php


namespace App\Shared\Exception;

class TeamNotFoundException extends \Exception implements ApiExceptionInterface
{
    public function __construct(string $teamId)
    {
        parent::__construct(sprintf('Team with ID - %s not found', $teamId), 404);
    }

    public function getStatusCode()
    {
        return $this->getCode();
    }
}