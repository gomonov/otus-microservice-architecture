<?php

namespace App\Application\Bonus\Model;

use DateTime;

interface BonusModelInterface
{
    public function getId(): ?int;

    public function getUserId(): int;

    public function setUserId(int $userId): static;

    public function getBonus(): int;

    public function setBonus(int $bonus): static;

    public function getCreatedAt(): DateTime;

    public function getUpdatedAt(): DateTime;
}