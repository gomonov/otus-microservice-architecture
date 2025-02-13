<?php

namespace App\UI\Advertisement\Controller\Create;

use App\Application\Advertisement\UseCase\AdvertisementCreateAction;
use App\UI\Advertisement\Controller\Create\Dto\AdvertisementCreateDto;
use App\UI\Advertisement\Dto\AdvertisementOutputData;
use App\UI\Service\Auth\AuthService;
use App\UI\Service\Converter\ExceptionConverter;
use App\UI\Advertisement\Controller\Create\Dto\AdvertisementEditDto;
use App\UI\Service\Response\ResponseJsonFactory;
use App\UI\Service\Converter\ConstraintViolationListConverter;
use App\Application\Exception\AbstractApplicationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Controller extends AbstractController
{
    public function __construct(
        private readonly ValidatorInterface               $validator,
        private readonly ResponseJsonFactory              $responseJsonFactory,
        private readonly ConstraintViolationListConverter $constraintViolationListConverter,
        private readonly AdvertisementCreateAction        $advertisementCreateAction,
        private readonly ExceptionConverter               $exceptionConverter,
        private readonly AuthService                      $authService,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $auth = $this->authService->getAuthData($request);
        if (null === $auth) {
            return $this->responseJsonFactory->createFailureResponse(Response::HTTP_UNAUTHORIZED, ['Unauthorized']);
        }

        $input = new AdvertisementCreateDto($auth, $request);

        $violationList = $this->validator->validate($input);

        if ($violationList->count() > 0) {
            return $this->responseJsonFactory->createFailureResponse(
                Response::HTTP_BAD_REQUEST,
                $this->constraintViolationListConverter->convertErrorListToArray($violationList)
            );
        }

        try {
            $advertisement = $this->advertisementCreateAction->do($input);
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