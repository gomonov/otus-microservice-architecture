<?php

namespace App\Infrastructure\Doctrine\Column;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

trait CreatedAt
{
    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    protected ?DateTime $createdAt = null;

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function onPrePersistCreatedAt(): void
    {
        if (!$this->createdAt) {
            $this->createdAt = new DateTime();
        }
    }
}
