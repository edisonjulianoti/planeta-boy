<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class PlanoSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'slug' => 'free',
                'price' => 0.00,
                'description' => 'Para começar — perfil gratuito com recursos básicos.',
                'features' => [
                    '1 perfil',
                    'Até 5 fotos',
                    'Visibilidade básica',
                    'Contato direto via WhatsApp',
                ],
                'active' => true,
                'theme' => ['color' => '#71717a', 'icon' => '☆'],
            ],
            [
                'name' => 'Silver',
                'slug' => 'silver',
                'price' => 39.90,
                'description' => 'Maior visibilidade com destaque nas buscas.',
                'features' => [
                    '1 perfil',
                    'Até 10 fotos',
                    '1 vídeo',
                    'WhatsApp, Telegram e Instagram',
                    'Destaque básico nas buscas',
                    'Estatísticas básicas',
                ],
                'active' => true,
                'theme' => ['color' => '#94a3b8', 'icon' => '✦'],
            ],
            [
                'name' => 'Gold',
                'slug' => 'gold',
                'price' => 79.90,
                'description' => 'Melhor custo-benefício — máximo impacto com recursos premium.',
                'features' => [
                    '2 perfis',
                    'Até 20 fotos',
                    '3 vídeos',
                    'WhatsApp, Telegram e Instagram',
                    'Destaque prioritário nas buscas',
                    'Destaque por cidade',
                    'Selo verificado',
                    'Estatísticas completas',
                    'Suporte prioritário',
                ],
                'active' => true,
                'theme' => ['color' => '#f59e0b', 'icon' => '★'],
            ],
            [
                'name' => 'Premium',
                'slug' => 'premium',
                'price' => 149.90,
                'description' => 'O topo do mercado — todos os recursos liberados.',
                'features' => [
                    '5 perfis',
                    'Fotos ilimitadas',
                    'Vídeos ilimitados',
                    'WhatsApp, Telegram e Instagram',
                    'Destaque máximo nas buscas',
                    'Destaque por cidade',
                    'Destaque na página inicial',
                    'Selo verificado',
                    'Estatísticas avançadas',
                    'Suporte prioritário',
                    'Perfil em destaque automático',
                ],
                'active' => true,
                'theme' => ['color' => '#8b5cf6', 'icon' => '♦'],
            ],
        ];

        foreach ($plans as $plan) {
            $savedPlan = Plan::updateOrCreate(
                ['slug' => $plan['slug']],
                [
                    'name' => $plan['name'],
                    'price' => $plan['price'],
                    'description' => $plan['description'],
                    'features' => $plan['features'],
                    'active' => $plan['active'],
                ]
            );

            // Generate placeholder SVG if plan doesn't have an image
            if (!$savedPlan->image) {
                $svg = $this->generatePlaceholderSvg($plan['theme']['color'], $plan['theme']['icon'], $plan['name']);
                $imagePath = 'plans/' . $savedPlan->id . '/placeholder.svg';
                Storage::disk('public')->put($imagePath, $svg);
                $savedPlan->update(['image' => $imagePath]);
                $this->command->info("Imagem gerada para o plano: {$plan['name']}");
            }
        }
    }

    private function generatePlaceholderSvg(string $color, string $icon, string $label): string
    {
        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200">
  <defs>
    <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:{$color};stop-opacity:0.3" />
      <stop offset="100%" style="stop-color:{$color};stop-opacity:0.1" />
    </linearGradient>
  </defs>
  <circle cx="100" cy="100" r="90" fill="url(#bg)" stroke="{$color}" stroke-width="2"/>
  <text x="100" y="85" text-anchor="middle" fill="{$color}" font-size="60" font-family="Arial, sans-serif">{$icon}</text>
  <text x="100" y="135" text-anchor="middle" fill="{$color}" font-size="18" font-family="Arial, sans-serif" font-weight="bold" letter-spacing="3">{$label}</text>
</svg>
SVG;
    }
}
