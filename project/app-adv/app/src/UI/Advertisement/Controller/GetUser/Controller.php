<?php

namespace App\UI\Advertisement\Controller\GetUser;

use App\Application\Advertisement\Repository\AdvertisementRepositoryInterface;
use App\UI\Advertisement\Dto\AdvertisementOutputData;
use App\UI\Service\Auth\AuthService;
use App\UI\Service\Response\ResponseJsonFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Controller extends AbstractController
{
    public function __construct(
        private readonly ResponseJsonFactory              $responseJsonFactory,
        private readonly AdvertisementRepositoryInterface $advertisementRepository,
        private readonly AuthService                      $authService,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $auth = $this->authService->getAuthData($request);
        if (null === $auth) {
            return $this->responseJsonFactory->createFailureResponse(Response::HTTP_UNAUTHORIZED, ['Unauthorized']);
        }

        $result = [];
        $advertisements = $this->advertisementRepository->findAllByUser($auth->getId());
        foreach ($advertisements as $advertisement) {
            $result[] = (new AdvertisementOutputData($advertisement))->jsonSerialize();
        }

        return $this->responseJsonFactory->createSuccessResponse($result);
    }
}