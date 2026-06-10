<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

final class AuthService
{
    public function register(RegisterRequest $request): User
    {
        $user = User::create($request->safe()->only(['name', 'email', 'password']));

        Auth::login($user);

        $request->session()->regenerate();

        return $user;
    }

    public function attempt(LoginRequest $request): bool
    {
        return Auth::attempt(
            $request->only('email', 'password'),
            $request->boolean('remember'),
        );
    }

    public function logout(): void
    {
        Auth::logout();
    }
}
