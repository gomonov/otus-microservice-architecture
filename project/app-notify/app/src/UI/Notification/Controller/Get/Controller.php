<?php

namespace App\UI\Notification\Controller\Get;

use App\Application\Notification\UseCase\NotificationGetAction;
use App\UI\Notification\Controller\Get\Dto\NotificationOutputData;
use App\UI\Service\Auth\AuthService;
use App\UI\Service\Response\ResponseJsonFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Controller extends AbstractController
{
    public function __construct(
        private readonly NotificationGetAction $notificationGetAction,
        private readonly ResponseJsonFactory   $responseJsonFactory,
        private readonly AuthService           $authService,
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

        $notifications = $this->notificationGetAction->do($userId);

        $result = [];
        foreach ($notifications as $notification) {
            $result[] = (new NotificationOutputData($notification))->jsonSerialize();
        }

        return $this->responseJsonFactory->createSuccessResponse($result);
    }
}