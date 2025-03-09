<?php


namespace App\Domain\Player\Model;

use App\Shared\ValueObjects\Uuid;

readonly class PlayerTeam
{
    public function __construct(
        private Uuid   $teamId,
        private string $teamName,
    ) {
    }

    /**
     * @return Uuid
     */
    public function getTeamId(): Uuid
    {
        return $this->teamId;
    }

    /**
     * @return string
     */
    public function getTeamName(): string
    {
        return $this->teamName;
    }
}
