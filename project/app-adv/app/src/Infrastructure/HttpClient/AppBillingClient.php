<?php

namespace App\Infrastructure\HttpClient;

use App\Application\AppBillingClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class AppBillingClient implements AppBillingClientInterface
{
    public function __construct(
        private string $token,
        private HttpClientInterface $appBillingClient,
    ) {
    }

    public function pay(int $sum, int $userId, string $idempotencyKey): bool
    {
        try {
            $response = $this->appBillingClient->request(
                'PUT',
                '/api/v1/srv/account/pay/' . $userId,
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
            $response = $this->appBillingClient->request(
                'PUT',
                '/api/v1/srv/account/rollback/' . $userId,
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