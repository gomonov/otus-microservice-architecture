<?php

namespace App\UI\User\Controller\Get;

use App\Application\Exception\AbstractApplicationException;
use App\Application\User\UseCase\UserGetAction;
use App\UI\Service\Auth\AuthService;
use App\UI\Service\Converter\ExceptionConverter;
use App\UI\Service\Response\ResponseJsonFactory;
use App\UI\User\Dto\UserOutputData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Controller extends AbstractController
{
    public function __construct(
        private readonly UserGetAction       $userGetAction,
        private readonly ResponseJsonFactory $responseJsonFactory,
        private readonly ExceptionConverter  $exceptionConverter,
        private readonly AuthService         $authService,
    ) {
    }

    public function __invoke(int $userId, Request $request): JsonResponse
    {
        $auth = $this->authService->getAuthData($request);
        if (null === $auth) {
            return $this->responseJsonFactory->createFailureResponse(Response::HTTP_UNAUTHORIZED, ['Unauthorized']);
        }

        if ($auth->getId() !== $userId) {
            return $this->responseJsonFactory->createFailureResponse(Response::HTTP_FORBIDDEN, ['Access denied']);
        }

        try {
            $user   = $this->userGetAction->do($userId);
            $output = new UserOutputData($user);
        } catch (AbstractApplicationException $e) {
            return $this->responseJsonFactory->createFailureResponse(
                Response::HTTP_BAD_REQUEST,
                [$this->exceptionConverter->convert($e)]
            );
        }

        return $this->responseJsonFactory->createSuccessResponse($output->jsonSerialize());
    }
}