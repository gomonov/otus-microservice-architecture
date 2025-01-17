<?php

namespace App\Application\Advertisement\Saga;

use App\Application\Advertisement\Model\AdvertisementModelInterface;

readonly class AdvCreateSagaDto
{
    public function __construct(
        private AdvertisementModelInterface $advertisementModel,
        private string                      $token,
        private string                      $email
    ) {
    }

    public function getAdvertisementModel(): AdvertisementModelInterface
    {
        return $this->advertisementModel;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}