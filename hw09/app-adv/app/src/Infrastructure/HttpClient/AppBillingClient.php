<?php

namespace App\Infrastructure\HttpClient;

use App\Application\AppBillingClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class AppBillingClient implements AppBillingClientInterface
{
    public function __construct(
        private HttpClientInterface $appBillingClient,
    ) {
    }

    public function pay(int $sum, int $userId, string $token, string $idempotencyKey): bool
    {
        try {
            $response = $this->appBillingClient->request(
                'PUT',
                '/api/v1/account/pay/' . $userId,
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
            $response = $this->appBillingClient->request(
                'PUT',
                '/api/v1/account/rollback/' . $userId,
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