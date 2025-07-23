<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function login(array $credentials): ?string
    {
        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return null;
        }

        return $token;
    }
        public function logout(): void
    {
        Auth::guard('api')->logout();
    }
}
