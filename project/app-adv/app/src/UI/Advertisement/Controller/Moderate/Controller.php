<?php

namespace App\UI\Advertisement\Controller\Moderate;

use App\Application\Advertisement\UseCase\AdvertisementModerateAction;
use App\UI\Advertisement\Controller\Moderate\Dto\AdvertisementModerateDto;
use App\UI\Advertisement\Dto\AdvertisementOutputData;
use App\UI\Service\Auth\AuthService;
use App\UI\Service\Converter\ExceptionConverter;
use App\UI\Service\Response\ResponseJsonFactory;
use App\Application\Exception\AbstractApplicationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Controller extends AbstractController
{
    public function __construct(
        private readonly ResponseJsonFactory              $responseJsonFactory,
        private readonly AdvertisementModerateAction      $advertisementModerateAction,
        private readonly ExceptionConverter               $exceptionConverter,
        private readonly AuthService                      $authService,
    ) {
    }

    public function __invoke(int $advId, Request $request): JsonResponse
    {
        $auth = $this->authService->getAuthData($request);
        if (null === $auth) {
            return $this->responseJsonFactory->createFailureResponse(Response::HTTP_UNAUTHORIZED, ['Unauthorized']);
        }

        $input = new AdvertisementModerateDto($auth, $advId);

        try {
            $advertisement = $this->advertisementModerateAction->do($input);
            $output = new AdvertisementOutputData($advertisement);
        } catch (AbstractApplicationException $e) {
            return $this->responseJsonFactory->createFailureResponse(
                Response::HTTP_BAD_REQUEST,
                [$this->exceptionConverter->convert($e)]
            );
        }

        return $this->responseJsonFactory->createSuccessResponse($output->jsonSerialize());
    }
}