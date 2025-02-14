<?php

namespace App\UI\Bonus\Controller\Add\Dto;

use App\Application\Bonus\UseCase\Contract\BonusAddDataInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Type;

class BonusAddDto implements BonusAddDataInterface
{
    private int $userId;

    #[NotBlank(message: 'Поле не может быть пустым')]
    #[Type("int")]
    #[Positive]
    private ?int $sum;

    #[NotBlank(message: 'Заголовок X-Idempotency-Key не может быть пустым')]
    #[Type("string")]
    private ?string $idempotencyKey;

    public function __construct(int $userId, Request $request)
    {
        $params = $request->toArray();

        $this->userId = $userId;
        $this->sum    = $params['sum'] ?? null;
        $this->idempotencyKey = $request->headers->get('X-Idempotency-Key');
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getSum(): int
    {
        return $this->sum;
    }

    public function getIdempotencyKey(): string
    {
        return $this->idempotencyKey;
    }
}