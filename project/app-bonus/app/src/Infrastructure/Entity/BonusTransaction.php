<?php

namespace App\Infrastructure\Entity;

use App\Application\Bonus\Model\BonusTransactionModelInterface;
use App\Infrastructure\Doctrine\Column\CreatedAt;
use App\Infrastructure\Doctrine\Column\UpdatedAt;
use App\Infrastructure\Repository\BonusTransactionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: BonusTransactionRepository::class)]
#[ORM\Index(name: "IDX_idempotencyKey", columns: ["idempotency_key"])]
class BonusTransaction implements BonusTransactionModelInterface
{
    use CreatedAt;
    use UpdatedAt;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Bonus $bonus = null;

    #[ORM\Column]
    private ?int $value = null;

    #[ORM\Column(length: 36)]
    private ?string $idempotencyKey = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBonus(): Bonus
    {
        return $this->bonus;
    }

    public function setBonus(Bonus $bonus): static
    {
        $this->bonus = $bonus;

        return $this;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function setValue(int $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getIdempotencyKey(): string
    {
        return $this->idempotencyKey;
    }

    public function setIdempotencyKey(string $idempotencyKey): static
    {
        $this->idempotencyKey = $idempotencyKey;

        return $this;
    }
}
