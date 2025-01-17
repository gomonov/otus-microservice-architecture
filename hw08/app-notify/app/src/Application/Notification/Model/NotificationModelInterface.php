<?php

namespace App\Application\Notification\Model;

use DateTime;

interface NotificationModelInterface
{
    public function getId(): ?int;

    public function getUserId(): int;

    public function setUserId(int $userId): static;

    public function getText(): string;

    public function setText(string $text): static;

    public function getEmail(): ?string;

    public function setEmail(string $email): static;

    public function getCreatedAt(): DateTime;
}