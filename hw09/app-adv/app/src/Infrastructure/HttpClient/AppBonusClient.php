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

    public function add(int $sum, int $userId, string $token, string $idempotencyKey): bool
    {
        try {
            $response = $this->appBonusClient->request(
                'PUT',
                '/api/v1/bonus/add/' . $userId,
                [
                    'json' => ['sum' => $sum],
                    'headers' => ['X-Auth-Token' => $token, 'X-Idempotency-Key' => $idempotencyKey],
                ]
            );

            $code = $response->getStatusCode();
        } catch (TransportExceptionInterface) {
            return false;
        }

        return 200 === $code;
    }

    public function rollback(int $userId, string $token, string $idempotencyKey): bool
    {
        try {
            $response = $this->appBonusClient->request(
                'PUT',
                '/api/v1/bonus/rollback/' . $userId,
                [
                    'headers' => ['X-Auth-Token' => $token, 'X-Idempotency-Key' => $idempotencyKey],
                ]
            );

            $code = $response->getStatusCode();
        } catch (TransportExceptionInterface) {
            return false;
        }

        return 200 === $code;
    }
}