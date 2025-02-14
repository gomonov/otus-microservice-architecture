<?php

namespace App\UI\Service\Converter\Error\Dto;

class Error
{
    private ?string $errorText = null;

    private ?string $fieldName = null;

    public function getFieldName(): ?string
    {
        return $this->fieldName;
    }

    public function setFieldName(string $field): self
    {
        $this->fieldName = $field;

        return $this;
    }

    public function getErrorText(): ?string
    {
        return $this->errorText;
    }

    public function setErrorText(string $error): self
    {
        $this->errorText = $error;

        return $this;
    }
}