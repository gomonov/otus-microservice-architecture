<?php

namespace App\UI\Service\Response;

use App\UI\Service\Response\Dto\FailureResponseData;
use App\UI\Service\Response\Dto\SuccessResponseData;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseJsonFactory
{
    public function createSuccessResponse(?array $data = null): JsonResponse
    {
        $responseData = new SuccessResponseData($data);

        return $this->createJsonResponse($responseData->jsonSerialize());
    }

    public function createFailureResponse(array $errors = []): JsonResponse
    {
        $responseData = new FailureResponseData($errors);

        return $this->createJsonResponse($responseData->jsonSerialize(), Response::HTTP_BAD_REQUEST);
    }

    public function createAuthorizationFailureResponse(array $errors = []): JsonResponse
    {
        $responseData = new FailureResponseData($errors);

        return $this->createJsonResponse($responseData->jsonSerialize(), Response::HTTP_UNAUTHORIZED);
    }

    protected function createJsonResponse(array $data, int $status = 200): JsonResponse
    {
        return (new JsonResponse($data, $status));
    }
}