<?php

namespace App\UI\Moderation;

use App\Application\Moderation\ModerationAdvDataInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class ModerationAdvDto implements ModerationAdvDataInterface
{
    #[NotBlank]
    #[Type("string")]
    private ?string $uuid;

    #[NotBlank]
    #[Type("string")]
    private ?string $text;

    public function __construct(array $data)
    {
        $this->uuid = $data["uuid"] ?? null;
        $this->text = $data['text'] ?? null;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getText(): string
    {
        return $this->text;
    }
}