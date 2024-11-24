<?php

namespace App\UI\Service\Converter;

use App\UI\Service\Converter\Error\Dto\Error;
use App\UI\Service\Converter\Error\ErrorFormatter;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

readonly class ConstraintViolationListConverter
{
    public function __construct(
        private ErrorFormatter $errorFormatter,
    ) {}

    public function convertErrorListToArray(ConstraintViolationListInterface $errorsList): array
    {
        $errors = [];

        /** @var ConstraintViolation $error */
        foreach ($errorsList as $error) {
            $errors[] = $this->errorFormatter->format($this->buildDto($error));
        }

        return $errors;
    }
    
    private function buildDto(ConstraintViolation $error): Error
    {
        return (new Error())
            ->setErrorText($error->getMessage())
            ->setFieldName($error->getPropertyPath());
    }
}