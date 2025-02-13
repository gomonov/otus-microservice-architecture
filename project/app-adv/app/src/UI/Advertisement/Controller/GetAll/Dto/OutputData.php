<?php

namespace App\UI\Advertisement\Controller\GetAll\Dto;

use App\Application\Advertisement\Model\AdvertisementModelInterface;

class OutputData
{
    private string $title;

    private string $text;

    public function __construct(AdvertisementModelInterface $advertisement)
    {
        $this->title = $advertisement->getTitle();
        $this->text  = $advertisement->getText();
    }

    public function jsonSerialize(): array
    {
        return [
            'title' => $this->title,
            'text'  => $this->text,
        ];
    }
}