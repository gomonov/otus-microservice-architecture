<?php

namespace App\Application\Account\Model;

use App\Infrastructure\Entity\Account;

interface AccountTransactionModelInterface
{
    public function getId(): ?int;

    public function getAccount(): Account;

    public function setAccount(Account $account): static;

    public function getValue(): int;

    public function setValue(int $value): static;

    public function getIdempotencyKey(): string;

    public function setIdempotencyKey(string $idempotencyKey): static;
}