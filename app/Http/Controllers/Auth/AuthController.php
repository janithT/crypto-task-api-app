<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(public AuthService $authService) {}


    /**
     * Register a new user.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->userRegister($request->validated());

            return apiResponseWithStatusCode($user, 'success', 'You have successfully registered', '', 200);
        } catch (\Exception $e) {
            return apiResponseWithStatusCode([], 'error', $e->getMessage(), '', 422);
        }
    }

    /**
     * Log in a user and return a JWT token.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $token = $this->authService->userLogin($request->validated());

            if (isset($token['error'])) {
                return apiResponseWithStatusCode([], 'error', $token['error'], '', 401);
            }

            return apiResponseWithStatusCode($token, 'success', 'You have successfully logged in', '', 200);
        } catch (\Exception $e) {
            return apiResponseWithStatusCode([], 'error', $e->getMessage(), '', 401);
        }
    }

    /**
     * Log in a user and return a JWT token.
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $this->authService->logout();
        return apiResponseWithStatusCode([], 'success', 'You have successfully logged out', '', 200);
    }
}
