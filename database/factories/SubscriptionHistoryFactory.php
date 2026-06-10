<?php

namespace Database\Factories;

use App\Models\Subscription;
use App\Models\SubscriptionHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SubscriptionHistory>
 */
class SubscriptionHistoryFactory extends Factory
{
    protected $model = SubscriptionHistory::class;

    public function definition(): array
    {
        return [
            'subscription_id' => Subscription::factory(),
            'event'           => 'created',
            'old_plan_slug'   => null,
            'new_plan_slug'   => null,
            'description'     => 'Assinatura criada.',
        ];
    }

    /**
     * Evento de renovação.
     */
    public function renewed(): static
    {
        return $this->state(fn (array $attributes) => [
            'event'       => 'renewed',
            'description' => 'Assinatura renovada automaticamente.',
        ]);
    }

    /**
     * Evento de cancelamento.
     */
    public function cancelled(?string $reason = null): static
    {
        return $this->state(fn (array $attributes) => [
            'event'       => 'cancelled',
            'description' => $reason ?? 'Assinatura cancelada pelo usuário.',
        ]);
    }

    /**
     * Evento de upgrade/downgrade de plano.
     */
    public function planChange(string $event, string $oldPlan, string $newPlan): static
    {
        return $this->state(fn (array $attributes) => [
            'event'         => $event,
            'old_plan_slug' => $oldPlan,
            'new_plan_slug' => $newPlan,
            'description'   => match ($event) {
                'upgraded'   => "Plano alterado de {$oldPlan} para {$newPlan}.",
                'downgraded' => "Plano alterado de {$oldPlan} para {$newPlan}.",
                default      => "Plano alterado: {$oldPlan} → {$newPlan}.",
            },
        ]);
    }
}
