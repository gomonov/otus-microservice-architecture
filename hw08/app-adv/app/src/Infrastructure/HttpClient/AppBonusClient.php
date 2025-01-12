<?php

namespace App\Infrastructure\HttpClient;

use App\Application\AppBonusClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class AppBonusClient implements AppBonusClientInterface
{
    public function __construct(
        private HttpClientInterface $appBonusClient,
    ) {
    }

    public function debit(int $sum, int $userId, string $token): bool
    {
        try {
            $response = $this->appBonusClient->request(
                'PUT',
                '/api/v1/bonus/debit/' . $userId,
                [
                    'json' => ['sum' => $sum],
                    'headers' => ['X-Auth-Token' => $token],
                ]
            );

            $code = $response->getStatusCode();
        } catch (TransportExceptionInterface) {
            return false;
        }

        return 200 === $code;
    }

    public function credit(int $sum, int $userId, string $token): bool
    {
        try {
            $response = $this->appBonusClient->request(
                'PUT',
                '/api/v1/bonus/credit/' . $userId,
                [
                    'json' => ['sum' => $sum],
                    'headers' => ['X-Auth-Token' => $token],
                ]
            );

            $code = $response->getStatusCode();
        } catch (TransportExceptionInterface) {
            return false;
        }

        return 200 === $code;
    }
}