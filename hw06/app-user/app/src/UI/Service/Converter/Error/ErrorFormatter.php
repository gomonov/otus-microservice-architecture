<?php

namespace App\UI\Service\Converter\Error;

use App\UI\Service\Converter\Error\Dto\Error;

class ErrorFormatter
{
    public function format(Error $error): array
    {
        $errorData['text'] = $error->getErrorText();

        if ($error->getFieldName()) {
            $errorData['field'] = $error->getFieldName();
        }

        return $errorData;
    }
}