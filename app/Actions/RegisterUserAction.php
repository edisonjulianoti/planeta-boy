<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

final class RegisterUserAction
{
    public function execute(array $data): User
    {
        $user = User::create($data);

        Auth::login($user);

        return $user;
    }
}
