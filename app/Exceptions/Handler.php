<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/v1/*')) {
                return response()->json([
                    'message' => 'Route not found.',
                ], 404);
            }
        });

        $this->renderable(function (MethodNotAllowedHttpException $e, Request $request) {
            if ($request->is('api/v1/*')) {
                return response()->json([
                    'message' => 'Method not allowed.',
                ], 405);
            }
        });
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return apiResponseWithStatusCode([], 'error', 'Unauthenticated access, Please login to access', '', 401);
    }
}