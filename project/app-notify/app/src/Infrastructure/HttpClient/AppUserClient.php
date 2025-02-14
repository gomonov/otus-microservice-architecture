<?php

namespace App\Infrastructure\HttpClient;

use App\Application\AppUserClientInterface;
use App\Application\Exception\UserServiceException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class AppUserClient implements AppUserClientInterface
{
    public function __construct(
        private string $token,
        private HttpClientInterface $appUserClient,
    ) {
    }

    /**
     * @param int $userId
     * @return string
     * @throws UserServiceException
     */
    public function getEmail(int $userId): string
    {
        try {
            $response = $this->appUserClient->request(
                'GET',
                '/api/v1/srv/user/email/' . $userId,
                [
                    'headers' => ['X-Service-Token' => $this->token],
                ]
            );

            $code = $response->getStatusCode();
            if (200 !== $code) {
                throw new UserServiceException();
            }

            $data = $response->toArray();

            if (false === empty($data['success']) && true === $data['success']) {
                return $data['data']['email'];
            }

            throw new UserServiceException();
        } catch (TransportExceptionInterface|ClientExceptionInterface|ServerExceptionInterface|DecodingExceptionInterface|RedirectionExceptionInterface) {
            throw new UserServiceException();
        }
    }
}