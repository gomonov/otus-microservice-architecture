<?php

namespace App\Infrastructure\Doctrine\Column;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

trait UpdatedAt
{
    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    protected DateTime $updatedAt;

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    #[ORM\PreUpdate]
    #[ORM\PrePersist]
    public function onPreUpdateUpdatedAt(): void
    {
        $this->updatedAt = new DateTime();
    }
}