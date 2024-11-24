<?php

namespace App\UI\User\Controller\Update;

use App\Application\Exception\AbstractApplicationException;
use App\Application\User\UseCase\UserUpdateAction;
use App\UI\Service\Converter\ConstraintViolationListConverter;
use App\UI\Service\Converter\ExceptionConverter;
use App\UI\Service\Response\ResponseJsonFactory;
use App\UI\User\Controller\Update\Dto\UserUpdateDto;
use App\UI\User\Dto\UserOutputData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Controller extends AbstractController
{
    public function __construct(
        private readonly ValidatorInterface               $validator,
        private readonly ResponseJsonFactory              $responseJsonFactory,
        private readonly ConstraintViolationListConverter $constraintViolationListConverter,
        private readonly UserUpdateAction                 $userUpdateAction,
        private readonly ExceptionConverter               $exceptionConverter,
    ) {
    }

    public function __invoke(int $userId, Request $request): JsonResponse
    {
        $input = new UserUpdateDto($userId, $request);

        $violationList = $this->validator->validate($input);

        if ($violationList->count() > 0) {
            return $this->responseJsonFactory->createFailureResponse(
                $this->constraintViolationListConverter->convertErrorListToArray($violationList)
            );
        }

        try {
            $user   = $this->userUpdateAction->do($input);
            $output = new UserOutputData($user);
        } catch (AbstractApplicationException $e) {
            return $this->responseJsonFactory->createFailureResponse([$this->exceptionConverter->convert($e)]);
        }

        return $this->responseJsonFactory->createSuccessResponse($output->jsonSerialize());
    }
}