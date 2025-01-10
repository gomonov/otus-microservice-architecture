<?php

namespace App\UI\Account\Dto;

use App\Application\Account\Model\AccountModelInterface;
use DateTime;
use JsonSerializable;

class AccountOutputData implements JsonSerializable
{
    private int $userId;

    private int $balance;

    private DateTime $createdAt;

    private DateTime $updatedAt;

    public function __construct(AccountModelInterface $account)
    {
        $this->userId    = $account->getUserId();
        $this->balance   = $account->getBalance();
        $this->createdAt = $account->getCreatedAt();
        $this->updatedAt = $account->getUpdatedAt();
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