<?php

declare(strict_types=1);

namespace App\Actions;

use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

final class RegisterUserAction
{
    public function execute(RegisterRequest $request): User
    {
        $user = User::create($request->safe()->only(['name', 'email', 'password']));

        Auth::login($user);

        $request->session()->regenerate();

        return $user;
    }
}
