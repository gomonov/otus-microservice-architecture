<?php

namespace App\UI\User\Controller\Auth;

use App\UI\Service\Auth\AuthService;
use App\UI\Service\Response\ResponseJsonFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Controller extends AbstractController
{
    public function __construct(
        private readonly ResponseJsonFactory $responseJsonFactory,
        private readonly AuthService         $authService
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $token = $this->authService->authenticate($request);
        if (null === $token) {
            $response = $this->responseJsonFactory->createSuccessResponse(
                ['Auth error']
            );
        } else {
            $response = $this->responseJsonFactory->createSuccessResponse();
            $response->headers->set('X-Auth-Token', $token);
        }

        return $response;
    }
}