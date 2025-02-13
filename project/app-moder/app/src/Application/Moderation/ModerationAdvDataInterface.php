<?php

namespace App\Application\Moderation;

interface ModerationAdvDataInterface
{
    public function getUuid(): string;

    public function getText(): string;
}