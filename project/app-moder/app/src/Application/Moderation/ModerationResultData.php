<?php

namespace App\Application\Moderation;

readonly class ModerationResultData
{
    public function __construct(private bool $verified, private string $reason)
    {
    }

    public function isVerified(): bool
    {
        return $this->verified;
    }

    public function getReason(): string
    {
        return $this->reason;
    }
}