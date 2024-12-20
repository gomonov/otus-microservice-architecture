<?php

namespace App\UI\User\Controller\Create;

use App\Application\User\UseCase\UserCreateAction;
use App\UI\Service\Converter\ExceptionConverter;
use App\UI\User\Controller\Create\Dto\UserCreateDto;
use App\UI\Service\Response\ResponseJsonFactory;
use App\UI\Service\Converter\ConstraintViolationListConverter;
use App\Application\Exception\AbstractApplicationException;
use App\UI\User\Dto\UserOutputData;
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
        private readonly UserCreateAction                 $userCreateAction,
        private readonly ExceptionConverter               $exceptionConverter,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $input = new UserCreateDto($request);

        $violationList = $this->validator->validate($input);

        if ($violationList->count() > 0) {
            return $this->responseJsonFactory->createFailureResponse(
                Response::HTTP_BAD_REQUEST,
                $this->constraintViolationListConverter->convertErrorListToArray($violationList)
            );
        }

        try {
            $user   = $this->userCreateAction->do($input);
            $output = new UserOutputData($user);
        } catch (AbstractApplicationException $e) {
            return $this->responseJsonFactory->createFailureResponse(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                [$this->exceptionConverter->convert($e)]
            );
        }

        return $this->responseJsonFactory->createSuccessResponse($output->jsonSerialize());
    }
}