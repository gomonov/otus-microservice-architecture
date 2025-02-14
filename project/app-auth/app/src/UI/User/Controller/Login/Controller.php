<?php

namespace App\UI\User\Controller\Login;

use App\Application\Exception\AbstractApplicationException;
use App\Application\User\UseCase\UserLoginAction;
use App\UI\Service\Converter\ConstraintViolationListConverter;
use App\UI\Service\Converter\ExceptionConverter;
use App\UI\Service\Response\ResponseJsonFactory;
use App\UI\User\Controller\Login\Dto\UserLoginDto;
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
        private readonly UserLoginAction                  $userLoginAction,
        private readonly ExceptionConverter               $exceptionConverter,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $input = new UserLoginDto($request);

        $violationList = $this->validator->validate($input);

        if ($violationList->count() > 0) {
            return $this->responseJsonFactory->createFailureResponse(
                Response::HTTP_BAD_REQUEST,
                $this->constraintViolationListConverter->convertErrorListToArray($violationList)
            );
        }

        try {
            $user = $this->userLoginAction->do($input);
            $request->getSession()->set('user_data', json_encode(['id' => $user->getId()], JSON_THROW_ON_ERROR));
        } catch (AbstractApplicationException $e) {
            return $this->responseJsonFactory->createFailureResponse(
                Response::HTTP_BAD_REQUEST,
                [$this->exceptionConverter->convert($e)]
            );
        }

        return $this->responseJsonFactory->createSuccessResponse();
    }
}