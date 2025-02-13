<?php

namespace App\UI\Notification\Consumer\Dto;

use App\Application\Notification\UseCase\Contract\NotificationCreateDataInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class NotificationCreateDto implements NotificationCreateDataInterface
{
    #[NotBlank]
    #[Type("int")]
    private ?int $userId;
    
    #[NotBlank]
    #[Type("string")]
    private ?string $text;

    public function __construct(array $data)
    {
        $this->userId = $data["userId"] ?? null;
        $this->text   = $data['text'] ?? null;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getText(): string
    {
        return $this->text;
    }
}