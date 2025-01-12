<?php

namespace App\UI\Bonus\Dto;

use App\Application\Bonus\Model\BonusModelInterface;
use DateTime;
use JsonSerializable;

class BonusOutputData implements JsonSerializable
{
    private int $userId;

    private int $balance;

    private DateTime $createdAt;

    private DateTime $updatedAt;

    public function __construct(BonusModelInterface $bonus)
    {
        $this->userId    = $bonus->getUserId();
        $this->balance   = $bonus->getBalance();
        $this->createdAt = $bonus->getCreatedAt();
        $this->updatedAt = $bonus->getUpdatedAt();
    }

    public function jsonSerialize(): array
    {
        return [
            'userId'    => $this->userId,
            'balance'   => $this->balance,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}