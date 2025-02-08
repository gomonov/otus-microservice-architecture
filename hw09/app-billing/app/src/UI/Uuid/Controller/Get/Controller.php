<?php

namespace App\UI\Uuid\Controller\Get;

use App\Application\Uuid\UuidGeneratorInterface;
use App\UI\Service\Response\ResponseJsonFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class Controller extends AbstractController
{
    public function __construct(
        private readonly ResponseJsonFactory    $responseJsonFactory,
        private readonly UuidGeneratorInterface $uuidGenerator,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        return $this->responseJsonFactory->createSuccessResponse(['uuid' => $this->uuidGenerator->generate()]);
    }
}