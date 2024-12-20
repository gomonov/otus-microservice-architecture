<?php

namespace App\UI\Service\Response\Dto;

use JsonSerializable;

class FailureResponseData implements JsonSerializable
{
    private array $errors;

    private bool $success;

    public function __construct(array $errors, bool $success = false)
    {
        $this->success = $success;
        $this->errors  = $errors;
    }

    public function jsonSerialize(): array
    {
        return [
            'success' => $this->success,
            'errors'  => $this->errors,
        ];
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }
}