<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'is_admin' => false,
            'plan' => 'free',
            'cpf' => fake()->numerify('###.###.###-##'),
            'data_nascimento' => fake()->date('Y-m-d', '-18 years'),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_admin' => true,
        ]);
    }

    /**
     * Indica que o usuário está bloqueado.
     */
    public function blocked(): static
    {
        return $this->state(fn (array $attributes) => [
            'blocked' => true,
        ]);
    }

    /**
     * Indica que o usuário tem plano premium ativo.
     */
    public function premium(): static
    {
        return $this->state(fn (array $attributes) => [
            'plan' => 'premium',
            'plan_expires_at' => now()->addMonth(),
        ]);
    }

    /**
     * Indica que o usuário tem plano premium expirado.
     */
    public function premiumExpired(): static
    {
        return $this->state(fn (array $attributes) => [
            'plan' => 'premium',
            'plan_expires_at' => now()->subMonth(),
        ]);
    }

    /**
     * Indica que o usuário tem telefone.
     */
    public function withPhone(): static
    {
        return $this->state(fn (array $attributes) => [
            'phone' => fake()->phoneNumber(),
        ]);
    }

    /**
     * Indica que o usuário tem bio.
     */
    public function withBio(): static
    {
        return $this->state(fn (array $attributes) => [
            'bio' => fake()->text(200),
        ]);
    }
}
