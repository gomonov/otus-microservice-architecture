<?php

namespace App\UI\Advertisement\Dto;

use App\Application\Advertisement\Model\AdvertisementModelInterface;
use App\Application\Advertisement\Model\AdvertisementStatusEnum;
use DateTime;
use JsonSerializable;

class AdvertisementOutputData implements JsonSerializable
{
    private int $id;

    private int $userId;

    private string $title;

    private string $text;

    private string $cost;

    private ?string $moderateFailedReason;

    private AdvertisementStatusEnum $status;

    private DateTime $createdAt;

    private DateTime $updatedAt;

    public function __construct(AdvertisementModelInterface $advertisement)
    {
        $this->id                   = $advertisement->getId();
        $this->userId               = $advertisement->getUserId();
        $this->title                = $advertisement->getTitle();
        $this->text                 = $advertisement->getText();
        $this->cost                 = $advertisement->getCost();
        $this->createdAt            = $advertisement->getCreatedAt();
        $this->updatedAt            = $advertisement->getUpdatedAt();
        $this->status               = $advertisement->getStatus();
        $this->moderateFailedReason = $advertisement->getModerFailReason();
    }

    public function jsonSerialize(): array
    {
        $result = [
            'id'        => $this->id,
            'userId'    => $this->userId,
            'title'     => $this->title,
            'text'      => $this->text,
            'cost'      => $this->cost,
            'status'    => $this->status->getFullName(),
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];

        if (null !== $this->moderateFailedReason) {
            $result['moderationFailedReason'] = $this->moderateFailedReason;
        }
        return $result;
    }
}