<?php

namespace App\Application\Bonus\Model;

use App\Infrastructure\Entity\Bonus;

interface BonusTransactionModelInterface
{
    public function getId(): ?int;

    public function getBonus(): Bonus;

    public function setBonus(Bonus $bonus): static;

    public function getValue(): int;

    public function setValue(int $value): static;

    public function getIdempotencyKey(): string;

    public function setIdempotencyKey(string $idempotencyKey): static;
}