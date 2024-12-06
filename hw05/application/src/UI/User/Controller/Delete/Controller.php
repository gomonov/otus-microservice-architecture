<?php

namespace App\UI\User\Controller\Delete;

use App\Application\Exception\AbstractApplicationException;
use App\Application\User\UseCase\UserDeleteAction;
use App\UI\Service\Converter\ExceptionConverter;
use App\UI\Service\Response\ResponseJsonFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class Controller extends AbstractController
{
    public function __construct(
        private readonly UserDeleteAction    $userDeleteAction,
        private readonly ResponseJsonFactory $responseJsonFactory,
        private readonly ExceptionConverter  $exceptionConverter,
    ) {
    }

    public function __invoke(int $userId): JsonResponse
    {
        try {
            $this->userDeleteAction->do($userId);
        } catch (AbstractApplicationException $e) {
            return $this->responseJsonFactory->createFailureResponse([$this->exceptionConverter->convert($e)]);
        }

        return $this->responseJsonFactory->createSuccessResponse();
    }
}