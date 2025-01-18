<?php

namespace App\UI\Service\Auth;

use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\Request;

readonly class AuthService
{
    public function __construct(private string $secret)
    {
    }

    public function authenticate(Request $request): ?string
    {
        $userData = $request->getSession()->get('user_data');

        if (null === $userData) {
            return null;
        }

        return JWT::encode(json_decode($userData, true), $this->secret, 'HS256');
    }
}