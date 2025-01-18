<?php

namespace App\UI\Account\Controller\Rollback\Dto;

use App\Application\Account\UseCase\Contract\AccountRollbackDataInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class AccountRollbackDto implements AccountRollbackDataInterface
{
    private int $userId;

    #[NotBlank(message: 'Заголовок X-Idempotency-Key не может быть пустым')]
    #[Type("string")]
    private ?string $idempotencyKey;

    public function __construct(int $userId, Request $request)
    {
        $this->userId = $userId;
        $this->idempotencyKey = $request->headers->get('X-Idempotency-Key');
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getIdempotencyKey(): string
    {
        return $this->idempotencyKey;
    }
}