<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    // Register a new user
    public function register(array $data):array{
        // Create user with hashed password
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'] ?? 'user',
        ]);

        // Generate JWT token
        $token = JWTAuth::fromUser($user);

        // Return user data + token
        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    // Login user and return token
    public function login(array $credentials): ?string
    {
        // Attempt authentication and return token
        return JWTAuth::attempt($credentials);
    }

    // Logout user (invalidate token)
    public function logout(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    // Get authenticated user
    public function getAuthenticatedUser(): ?User
    {
        return JWTAuth::parseToken()->authenticate();
    }
}
?>