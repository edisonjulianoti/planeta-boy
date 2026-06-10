<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Profile;
use App\Models\Subscription;
use App\Models\SubscriptionHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $plans = Plan::where('active', true)->get()->keyBy('slug');
        $users = User::where('is_admin', false)->get();
        $profiles = Profile::all();

        if ($users->isEmpty() || $plans->isEmpty()) {
            $this->command->warn('Execute outros seeders primeiro (UserSeeder, PlanoSeeder).');
            return;
        }

        $planSlugs = ['free', 'silver', 'gold', 'premium'];

        // ─── 20 subscriptions: mix de usuários comuns + perfis de acompanhante ───

        for ($i = 0; $i < 20; $i++) {
            $user = $users->random();
            $slug = $planSlugs[array_rand($planSlugs)];
            $plan = $plans->get($slug) ?? $plans->first();
            $startDate = Carbon::now()->subMonths(rand(1, 6));

            $data = [
                'user_id'    => $user->id,
                'plan_id'    => $plan->id,
                'profile_id' => null,
                'status'     => 'active',
                'start_date' => $startDate,
                'end_date'   => $plan->price > 0 ? (clone $startDate)->addMonths(rand(1, 3)) : null,
            ];

            // 30% chance de ser uma assinatura de perfil (usuário assinando perfil de acompanhante)
            if ($profiles->isNotEmpty() && rand(1, 100) <= 30) {
                $data['profile_id'] = $profiles->random()->id;
            }

            // 20% chance de cancelada/expirada
            if (rand(1, 100) <= 20) {
                $data['status'] = 'cancelled';
                $data['end_date'] = Carbon::now()->subDays(rand(1, 30));
            }

            Subscription::create($data);
        }

        // ─── +10 subscriptions extras para perfis com assinantes ───
        if ($profiles->isNotEmpty()) {
            for ($i = 0; $i < 10; $i++) {
                $profile = $profiles->random();
                $user = $users->random();
                $slug = $planSlugs[array_rand($planSlugs)];
                $plan = $plans->get($slug) ?? $plans->first();
                $startDate = Carbon::now()->subMonths(rand(1, 5));

                Subscription::create([
                    'user_id'    => $user->id,
                    'plan_id'    => $plan->id,
                    'profile_id' => $profile->id,
                    'status'     => 'active',
                    'start_date' => $startDate,
                    'end_date'   => $plan->price > 0 ? (clone $startDate)->addMonths(rand(1, 3)) : null,
                ]);
            }
        }

        $this->command->info('SubscriptionSeeder: ' . Subscription::count() . ' assinaturas criadas.');
    }
}
