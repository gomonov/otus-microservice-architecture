<?php

namespace App\UI\User\Controller\Get;

use App\Application\Exception\AbstractApplicationException;
use App\Application\User\UseCase\UserGetAction;
use App\UI\Service\Converter\ExceptionConverter;
use App\UI\Service\Response\ResponseJsonFactory;
use App\UI\User\Dto\UserOutputData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class Controller extends AbstractController
{
    public function __construct(
        private readonly UserGetAction       $userGetAction,
        private readonly ResponseJsonFactory $responseJsonFactory,
        private readonly ExceptionConverter  $exceptionConverter,
    ) {
    }

    public function __invoke(int $userId): JsonResponse
    {
        try {
            $user   = $this->userGetAction->do($userId);
            $output = new UserOutputData($user);
        } catch (AbstractApplicationException $e) {
            return $this->responseJsonFactory->createFailureResponse([$this->exceptionConverter->convert($e)]);
        }

        return $this->responseJsonFactory->createSuccessResponse($output->jsonSerialize());
    }
}