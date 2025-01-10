<?php

namespace App\Application\Kafka;

interface MessageFabricInterface
{
    public function create(): MessageInterface;
}