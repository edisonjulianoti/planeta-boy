<?php

namespace Database\Seeders;

use App\Models\SubscriptionRequest;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class SubscriptionRequestSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('is_admin', false)->get();
        $plans = ['free', 'basic', 'premium'];
        $statuses = ['pending', 'approved', 'rejected'];
        $adminNotes = [
            'Usuário verificado manualmente',
            'Documentação completa',
            'Aguardando pagamento',
            'Pagamento confirmado',
            'Perfil incompleto',
            'Informações insuficientes',
            'Aprovação rápida',
            null,
            null,
            null,
        ];

        // Criar 50 registros de assinaturas
        for ($i = 0; $i < 50; $i++) {
            $user = $users->random();
            $plan = $plans[array_rand($plans)];

            // Distribuir status: 40% pending, 40% approved, 20% rejected
            $rand = rand(1, 100);
            if ($rand <= 40) {
                $status = 'pending';
            } elseif ($rand <= 80) {
                $status = 'approved';
            } else {
                $status = 'rejected';
            }

            // Gerar data de expiração aleatória
            $expiresAt = null;
            if ($status === 'approved') {
                $daysOffset = rand(-30, 90);
                $expiresAt = Carbon::now()->addDays($daysOffset);
            }

            SubscriptionRequest::create([
                'user_id' => $user->id,
                'plan_slug' => $plan,
                'status' => $status,
                'admin_notes' => $adminNotes[array_rand($adminNotes)],
                'expires_at' => $expiresAt,
            ]);
        }
    }
}
