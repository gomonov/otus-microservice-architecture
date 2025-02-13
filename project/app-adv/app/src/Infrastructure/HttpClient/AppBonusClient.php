<?php

namespace App\Infrastructure\HttpClient;

use App\Application\AppBonusClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class AppBonusClient implements AppBonusClientInterface
{
    public function __construct(
        private string $token,
        private HttpClientInterface $appBonusClient,
    ) {
    }

    public function add(int $sum, int $userId, string $idempotencyKey): bool
    {
        try {
            $response = $this->appBonusClient->request(
                'PUT',
                '/api/v1/srv/bonus/add/' . $userId,
                [
                    'json' => ['sum' => $sum],
                    'headers' => ['X-Service-Token' => $this->token, 'X-Idempotency-Key' => $idempotencyKey],
                ]
            );

            $code = $response->getStatusCode();
        } catch (TransportExceptionInterface) {
            return false;
        }

        return 200 === $code;
    }

    public function rollback(int $userId, string $idempotencyKey): bool
    {
        try {
            $response = $this->appBonusClient->request(
                'PUT',
                '/api/v1/srv/bonus/rollback/' . $userId,
                [
                    'headers' => ['X-Service-Token' => $this->token, 'X-Idempotency-Key' => $idempotencyKey],
                ]
            );

            $code = $response->getStatusCode();
        } catch (TransportExceptionInterface) {
            return false;
        }

        return 200 === $code;
    }
}