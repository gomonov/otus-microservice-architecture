<?php

declare(strict_types=1);

namespace App\UI\HealthCheck;

use App\UI\Service\Response\ResponseJsonFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class Controller extends AbstractController
{
    public function __construct(private readonly ResponseJsonFactory $responseJsonFactory)
    {
    }

    public function __invoke(): JsonResponse
    {
        return $this->responseJsonFactory->createSuccessResponse();
    }
}
