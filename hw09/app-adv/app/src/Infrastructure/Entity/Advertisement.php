<?php

namespace App\Infrastructure\Entity;

use App\Application\Advertisement\Model\AdvertisementModelInterface;
use App\Application\Advertisement\Model\AdvertisementStatusEnum;
use App\Infrastructure\Doctrine\Column\CreatedAt;
use App\Infrastructure\Doctrine\Column\UpdatedAt;
use App\Infrastructure\Repository\AdvertisementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdvertisementRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name: "UNIQ_user", columns: ["user_id"])]
#[ORM\UniqueConstraint(name: "UNIQ_idempotencyKey", columns: ["idempotency_key"])]
class Advertisement implements AdvertisementModelInterface
{
    use CreatedAt;
    use UpdatedAt;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\Column]
    private ?int $cost = null;

    #[ORM\Column]
    private ?int $userId = null;

    #[ORM\Column(length: 36)]
    private ?string $idempotencyKey = null;

    #[ORM\Column(enumType: AdvertisementStatusEnum::class)]
    private ?AdvertisementStatusEnum $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getCost(): int
    {
        return $this->cost;
    }

    public function setCost(int $cost): static
    {
        $this->cost = $cost;

        return $this;
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

    public function getIdempotencyKey(): string
    {
        return $this->idempotencyKey;
    }

    public function setIdempotencyKey(string $idempotencyKey): static
    {
        $this->idempotencyKey = $idempotencyKey;

        return $this;
    }

    public function getStatus(): AdvertisementStatusEnum
    {
        return $this->status;
    }

    public function setStatus(AdvertisementStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }
}
