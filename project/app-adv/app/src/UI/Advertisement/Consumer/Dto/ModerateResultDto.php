<?php

namespace App\UI\Advertisement\Consumer\Dto;

use App\Application\Advertisement\UseCase\Contract\ModerateResultDataInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Contracts\Service\Attribute\Required;

class ModerateResultDto implements ModerateResultDataInterface
{
    #[NotBlank]
    #[Type("string")]
    private ?string $uuid;

    #[Required]
    #[Type("boolean")]
    private ?bool $verified;

    #[Required]
    #[Type("string")]
    private ?string $reason;

    public function __construct(array $data)
    {
        $this->uuid = $data["uuid"] ?? null;
        $this->verified = $data["verified"] ?? null;
        $this->reason = $data['reason'] ?? null;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getVerified(): bool
    {
        return $this->verified;
    }

    public function getReason(): string
    {
        return $this->reason;
    }
}