<?php

namespace App\UI\User\Controller\Email;

use App\Application\Exception\AbstractApplicationException;
use App\Application\User\UseCase\UserGetAction;
use App\UI\Service\Auth\AuthService;
use App\UI\Service\Converter\ExceptionConverter;
use App\UI\Service\Response\ResponseJsonFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Controller extends AbstractController
{
    public function __construct(
        private readonly UserGetAction $userGetAction,
        private readonly ResponseJsonFactory $responseJsonFactory,
        private readonly ExceptionConverter $exceptionConverter,
        private readonly AuthService $authService,
    ) {
    }

    public function __invoke(int $userId, Request $request): JsonResponse
    {
        if (false === $this->authService->isService($request)) {
            return $this->responseJsonFactory->createFailureResponse(Response::HTTP_UNAUTHORIZED, ['Unauthorized']);
        }

        try {
            $user = $this->userGetAction->do($userId);
        } catch (AbstractApplicationException $e) {
            return $this->responseJsonFactory->createFailureResponse(
                Response::HTTP_BAD_REQUEST,
                [$this->exceptionConverter->convert($e)]
            );
        }

        return $this->responseJsonFactory->createSuccessResponse(['email' => $user->getEmail()]);
    }
}