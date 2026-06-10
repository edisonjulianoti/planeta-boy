<?php

namespace Database\Factories;

use App\Models\Plan;
use App\Models\Profile;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subscription>
 */
class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-6 months', 'now');
        $plan = Plan::inRandomOrder()->first() ?? Plan::factory()->create();

        return [
            'user_id'    => User::factory(),
            'plan_id'    => $plan->id,
            'profile_id' => null,
            'status'     => 'active',
            'start_date' => $startDate,
            'end_date'   => $plan->slug === 'free' ? null : fake()->optional(0.3)->dateTimeBetween('now', '+3 months'),
        ];
    }

    /**
     * Assinatura vinculada a um perfil de acompanhante.
     */
    public function forProfile(Profile $profile): static
    {
        return $this->state(fn (array $attributes) => [
            'profile_id' => $profile->id,
            'user_id'    => User::factory(),
        ]);
    }

    /**
     * Assinatura cancelada.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'   => 'cancelled',
            'end_date' => fake()->dateTimeBetween('-3 months', 'now'),
        ]);
    }

    /**
     * Assinatura expirada.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'   => 'expired',
            'end_date' => fake()->dateTimeBetween('-6 months', '-1 day'),
        ]);
    }

    /**
     * Assinatura com um plano específico.
     */
    public function withPlan(string $slug): static
    {
        return $this->state(fn (array $attributes) => [
            'plan_id' => Plan::where('slug', $slug)->first()?->id ?? Plan::factory()->create(['slug' => $slug])->id,
        ]);
    }
}
