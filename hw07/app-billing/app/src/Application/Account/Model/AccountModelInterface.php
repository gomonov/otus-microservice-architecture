<?php

namespace App\Application\Account\Model;

use DateTime;

interface AccountModelInterface
{
    public function getId(): ?int;

    public function getUserId(): int;

    public function setUserId(int $userId): static;

    public function getBalance(): int;

    public function setBalance(int $balance): static;

    public function getCreatedAt(): DateTime;

    public function getUpdatedAt(): DateTime;
}