<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected AuthService $authService;

    // Dependency injection of AuthService
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    // Register a new user
    public function register(Request $request): JsonResponse
    {
        $result = $this->authService->register($request->all());
        return response()->json($result, 201);
    }

    // Login user
    public function login(Request $request): JsonResponse
    {
        $result = $this->authService->login($request->all());

        return response()->json([
            'token' => $result,
        ]);
    }

    /**
     * Logout user
     */
    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    // Get authenticated user
    public function me(): JsonResponse
    {
        $user = $this->authService->getAuthenticatedUser();

        return response()->json($user);
    }
}
