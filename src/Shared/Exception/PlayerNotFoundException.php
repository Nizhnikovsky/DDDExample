<?php

namespace App\Shared\Exception;

class PlayerNotFoundException extends \Exception implements ApiExceptionInterface
{
    public function __construct(string $playerId)
    {
        parent::__construct(sprintf('Player with ID - %s not found', $playerId), 404);
    }

    public function getStatusCode()
    {
        return $this->getCode();
    }
}
