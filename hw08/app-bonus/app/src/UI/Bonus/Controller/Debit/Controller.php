<?php

namespace App\UI\Bonus\Controller\Debit;

use App\Application\Bonus\UseCase\BonusDebitAction;
use App\Application\Exception\AbstractApplicationException;
use App\UI\Bonus\Controller\Dto\BonusChangeDto;
use App\UI\Bonus\Dto\BonusOutputData;
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
        private readonly BonusDebitAction                 $bonusDebitAction,
        private readonly ExceptionConverter               $exceptionConverter,
        private readonly AuthService                      $authService,
    ) {
    }

    public function __invoke(int $userId, Request $request): JsonResponse
    {
        $auth = $this->authService->getAuthData($request);
        if (null === $auth) {
            return $this->responseJsonFactory->createFailureResponse(Response::HTTP_UNAUTHORIZED, ['Unauthorized']);
        }

        $input = new BonusChangeDto($userId, $request);

        if ($auth->getId() !== $input->getUserId()) {
            return $this->responseJsonFactory->createFailureResponse(Response::HTTP_FORBIDDEN, ['Access denied']);
        }

        $violationList = $this->validator->validate($input);

        if ($violationList->count() > 0) {
            return $this->responseJsonFactory->createFailureResponse(
                Response::HTTP_BAD_REQUEST,
                $this->constraintViolationListConverter->convertErrorListToArray($violationList)
            );
        }

        try {
            $user   = $this->bonusDebitAction->do($input);
            $output = new BonusOutputData($user);
        } catch (AbstractApplicationException $e) {
            return $this->responseJsonFactory->createFailureResponse(
                Response::HTTP_BAD_REQUEST,
                [$this->exceptionConverter->convert($e)]
            );
        }

        return $this->responseJsonFactory->createSuccessResponse($output->jsonSerialize());
    }
}