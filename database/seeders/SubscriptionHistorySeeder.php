<?php

namespace Database\Seeders;

use App\Models\Subscription;
use App\Models\SubscriptionHistory;
use Illuminate\Database\Seeder;

class SubscriptionHistorySeeder extends Seeder
{
    public function run(): void
    {
        $subscriptions = Subscription::all();

        if ($subscriptions->isEmpty()) {
            $this->command->warn('Nenhuma assinatura encontrada. Execute SubscriptionSeeder primeiro.');
            return;
        }

        $events = ['created', 'renewed', 'cancelled', 'expired', 'upgraded', 'downgraded'];
        $planSlugs = ['free', 'silver', 'gold', 'premium'];

        foreach ($subscriptions as $sub) {
            $numHistories = rand(3, 5);

            for ($i = 0; $i < $numHistories; $i++) {
                $event = $events[array_rand($events)];
                $oldPlan = $planSlugs[array_rand($planSlugs)];
                $newPlan = $planSlugs[array_rand($planSlugs)];

                // Garantir que old/new sejam diferentes pra upgraded/downgraded
                if (in_array($event, ['upgraded', 'downgraded']) && $oldPlan === $newPlan) {
                    $newPlan = $planSlugs[array_rand($planSlugs)];
                }

                $description = match ($event) {
                    'created'   => 'Assinatura criada.',
                    'renewed'   => 'Assinatura renovada automaticamente.',
                    'cancelled' => 'Assinatura cancelada pelo usuário.',
                    'expired'   => 'Assinatura expirou.',
                    'upgraded'  => "Plano alterado de {$oldPlan} para {$newPlan}.",
                    'downgraded' => "Plano alterado de {$oldPlan} para {$newPlan}.",
                    default     => 'Evento de assinatura.',
                };

                SubscriptionHistory::create([
                    'subscription_id' => $sub->id,
                    'event'           => $event,
                    'old_plan_slug'   => in_array($event, ['upgraded', 'downgraded']) ? $oldPlan : null,
                    'new_plan_slug'   => in_array($event, ['upgraded', 'downgraded']) ? $newPlan : null,
                    'description'     => $description,
                    'created_at'      => $sub->start_date->copy()->addDays(rand(1, 30) * ($i + 1)),
                    'updated_at'      => $sub->start_date->copy()->addDays(rand(1, 30) * ($i + 1)),
                ]);
            }
        }

        $this->command->info('SubscriptionHistorySeeder: ' . SubscriptionHistory::count() . ' eventos criados.');
    }
}
