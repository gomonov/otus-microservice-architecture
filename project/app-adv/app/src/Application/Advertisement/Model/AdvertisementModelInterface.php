<?php

namespace App\Application\Advertisement\Model;

use DateTime;

interface AdvertisementModelInterface
{
    public function getId(): ?int;

    public function getTitle(): string;

    public function setTitle(string $title): static;

    public function getText(): string;

    public function setText(string $text): static;

    public function getCost(): int;

    public function getUserId(): int;

    public function setUserId(int $userId): static;

    public function getCreatedAt(): DateTime;

    public function getUpdatedAt(): DateTime;

    public function getIdempotencyKey(): string;

    public function setIdempotencyKey(string $idempotencyKey): static;

    public function getStatus(): AdvertisementStatusEnum;

    public function setStatus(AdvertisementStatusEnum $status): static;

    public function getModerKey(): ?string;

    public function setModerKey(?string $moderKey): static;

    public function getModerFailReason(): ?string;

    public function setModerFailReason(?string $moderFailReason): static;
}