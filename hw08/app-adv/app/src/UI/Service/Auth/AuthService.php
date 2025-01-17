<?php

namespace App\UI\Service\Auth;

use App\UI\Service\Auth\ValueObject\AuthValueObject;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

readonly class AuthService
{
    public function __construct(private string $secret)
    {
    }

    public function getAuthData(Request $request): ?AuthValueObject
    {
        $token = $request->headers->get('X-Auth-Token');
        if (null === $token) {
            return null;
        }

        try {
            $decoded = (array)JWT::decode($token, new Key($this->secret, 'HS256'));

            return new AuthValueObject($decoded['id'], $decoded['username'], $decoded['email'], $token);
        } catch (Throwable) {
            return null;
        }
    }
}