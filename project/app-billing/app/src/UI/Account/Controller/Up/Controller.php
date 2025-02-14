<?php

namespace App\UI\Account\Controller\Up;

use App\Application\Account\UseCase\AccountTopUpAction;
use App\Application\Exception\AbstractApplicationException;
use App\UI\Account\Controller\Dto\AccountChangeDto;
use App\UI\Account\Dto\AccountOutputData;
use App\UI\Service\Auth\AuthService;
use App\UI\Service\Converter\ConstraintViolationListConverter;
use App\UI\Service\Converter\ExceptionConverter;
use App\UI\Service\Response\ResponseJsonFactory;
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
        private readonly AccountTopUpAction               $accountTopUpAction,
        private readonly ExceptionConverter               $exceptionConverter,
        private readonly AuthService                      $authService,
    ) {
    }

    public function __invoke(int $userId, Request $request): JsonResponse
    {
        if (false === $this->authService->isService($request)) {
            return $this->responseJsonFactory->createFailureResponse(Response::HTTP_UNAUTHORIZED, ['Unauthorized']);
        }

        $input = new AccountChangeDto($userId, $request);

        $violationList = $this->validator->validate($input);

        if ($violationList->count() > 0) {
            return $this->responseJsonFactory->createFailureResponse(
                Response::HTTP_BAD_REQUEST,
                $this->constraintViolationListConverter->convertErrorListToArray($violationList)
            );
        }

        try {
            $user   = $this->accountTopUpAction->do($input);
            $output = new AccountOutputData($user);
        } catch (AbstractApplicationException $e) {
            return $this->responseJsonFactory->createFailureResponse(
                Response::HTTP_BAD_REQUEST,
                [$this->exceptionConverter->convert($e)]
            );
        }

        return $this->responseJsonFactory->createSuccessResponse($output->jsonSerialize());
    }
}