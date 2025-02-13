<?php

namespace App\UI\Notification\Controller\Get\Dto;

use App\Application\Notification\Model\NotificationModelInterface;
use DateTime;
use JsonSerializable;

class NotificationOutputData implements JsonSerializable
{
    private int $userId;

    private string $email;

    private string $text;

    private DateTime $createdAt;

    public function __construct(NotificationModelInterface $notification)
    {
        $this->userId    = $notification->getUserId();
        $this->email     = $notification->getEmail();
        $this->text      = $notification->getText();
        $this->createdAt = $notification->getCreatedAt();
    }

    public function jsonSerialize(): array
    {
        return [
            'userId'    => $this->userId,
            'email'     => $this->email,
            'text'      => $this->text,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}