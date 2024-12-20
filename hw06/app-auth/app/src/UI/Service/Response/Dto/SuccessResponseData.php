<?php

namespace App\UI\Service\Response\Dto;

use JsonSerializable;

class SuccessResponseData implements JsonSerializable
{
    private bool $success;

    private ?array $data;

    public function __construct(?array $data, bool $success = true)
    {
        $this->success = $success;
        $this->data    = $data;
    }

    public function jsonSerialize(): array
    {
        $result['success'] = $this->success;

        if (!is_null($this->data)) {
            $result['data'] = $this->data;
        }

        return $result;
    }
}