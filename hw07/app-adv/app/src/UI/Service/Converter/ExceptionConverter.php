<?php

namespace App\UI\Service\Converter;

use App\UI\Service\Converter\Error\Dto\Error;
use App\UI\Service\Converter\Error\ErrorFormatter;
use Throwable;

readonly class ExceptionConverter
{
    public function __construct(
        private ErrorFormatter $errorFormatter
    ) {}

    public function convert(Throwable $exception): array
    {
        return $this->errorFormatter->format($this->buildDto($exception));
    }

    private function buildDto(Throwable $exception): Error
    {
        return (new Error())
            ->setErrorText($exception->getMessage());
    }
}