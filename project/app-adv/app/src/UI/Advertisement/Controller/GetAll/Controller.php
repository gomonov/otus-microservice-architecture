<?php

namespace App\UI\Advertisement\Controller\GetAll;

use App\Application\Advertisement\Repository\AdvertisementRepositoryInterface;
use App\UI\Advertisement\Controller\GetAll\Dto\OutputData;
use App\UI\Service\Response\ResponseJsonFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class Controller extends AbstractController
{
    public function __construct(
        private readonly ResponseJsonFactory              $responseJsonFactory,
        private readonly AdvertisementRepositoryInterface $advertisementRepository,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $result = [];
        $advertisements = $this->advertisementRepository->findAllPublish();
        foreach ($advertisements as $advertisement) {
            $result[] = (new OutputData($advertisement))->jsonSerialize();
        }

        return $this->responseJsonFactory->createSuccessResponse($result);
    }
}