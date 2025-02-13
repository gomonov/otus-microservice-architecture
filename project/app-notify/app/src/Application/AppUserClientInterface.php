<?php

namespace App\Application;

interface AppUserClientInterface
{
    public function getEmail(int $userId): string;
}