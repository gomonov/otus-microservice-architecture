<?php

namespace App\UI\Bonus\Dto;

use App\Application\Bonus\Model\BonusModelInterface;
use DateTime;
use JsonSerializable;

class BonusOutputData implements JsonSerializable
{
    private int $userId;

    private int $bonus;

    private DateTime $createdAt;

    private DateTime $updatedAt;

    public function __construct(BonusModelInterface $bonus)
    {
        $this->userId    = $bonus->getUserId();
        $this->bonus     = $bonus->getBonus();
        $this->createdAt = $bonus->getCreatedAt();
        $this->updatedAt = $bonus->getUpdatedAt();
    }

    public function jsonSerialize(): array
    {
        return [
            'userId'    => $this->userId,
            'bonus'     => $this->bonus,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}