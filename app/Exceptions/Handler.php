<?php

// app/Exceptions/Handler.php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json([
            'success' => false,
            'message' => 'Unauthenticated. Please login first.',
            'data' => null,
        ], 401);
    }

    public function register(): void
    {
        // Daftarkan exception lain jika perlu
    }
}
