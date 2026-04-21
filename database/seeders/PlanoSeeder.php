<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanoSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Grátis',
                'slug' => 'free',
                'price' => 0.00,
                'description' => 'Plano gratuito com recursos básicos para começar.',
                'features' => ['1 perfil', '3 fotos', 'Listagem básica'],
                'active' => true,
            ],
            [
                'name' => 'Essencial',
                'slug' => 'essential',
                'price' => 29.90,
                'description' => 'Plano essencial com mais visibilidade.',
                'features' => ['1 perfil', '10 fotos', 'Destaque na listagem', 'WhatsApp visível'],
                'active' => true,
            ],
            [
                'name' => 'Premium',
                'slug' => 'premium',
                'price' => 79.90,
                'description' => 'Plano premium com todos os recursos.',
                'features' => ['3 perfis', 'Fotos ilimitadas', 'Top destaque', 'Verificação de perfil', 'Estatísticas avançadas'],
                'active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}
