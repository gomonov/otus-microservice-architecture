<?php

namespace App\Infrastructure\Entity;

use App\Application\Account\Model\AccountModelInterface;
use App\Infrastructure\Doctrine\Column\CreatedAt;
use App\Infrastructure\Doctrine\Column\UpdatedAt;
use App\Infrastructure\Repository\AccountRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name: "UNIQ_user_id", columns: ["user_id"])]
class Account implements AccountModelInterface
{
    use CreatedAt;
    use UpdatedAt;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $userId = null;

    #[ORM\Column]
    private ?int $balance = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function setBalance(int $balance): static
    {
        $this->balance = $balance;

        return $this;
    }
}
