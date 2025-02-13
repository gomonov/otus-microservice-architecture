<?php

namespace App\UI\Bonus\Controller\Dto;

use App\Application\Bonus\UseCase\Contract\BonusChangeDataInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Type;

class BonusChangeDto implements BonusChangeDataInterface
{
    private int $userId;

    #[NotBlank(message: 'Поле не может быть пустым')]
    #[Type("int")]
    #[Positive]
    private ?int $sum;

    public function __construct(int $userId, Request $request)
    {
        $params = $request->toArray();

        $this->userId = $userId;
        $this->sum    = $params['sum'] ?? null;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getSum(): int
    {
        return $this->sum;
    }
}