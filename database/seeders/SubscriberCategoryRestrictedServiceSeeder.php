<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\SubscriberCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriberCategoryRestrictedServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $basico = SubscriberCategory::where('slug', 'basico')->first();
        $intermediario = SubscriberCategory::where('slug', 'intermediario')->first();
        $avancado = SubscriberCategory::where('slug', 'avancado')->first();

        // Categoria Básico - Mais restrições
        if ($basico) {
            $restrictedServices = [
                'boquete',
                'sexo-anal',
                'bdsm',
                'dominacao',
                'fetiche',
                'massagem-erotica',
                'role-play',
            ];

            foreach ($restrictedServices as $slug) {
                $service = Service::where('slug', $slug)->first();
                if ($service) {
                    $basico->restrictedServices()->syncWithoutDetaching([$service->id]);
                }
            }
        }

        // Categoria Intermediário - Menos restrições
        if ($intermediario) {
            $restrictedServices = [
                'bdsm',
                'dominacao',
                'fetiche',
                'role-play',
            ];

            foreach ($restrictedServices as $slug) {
                $service = Service::where('slug', $slug)->first();
                if ($service) {
                    $intermediario->restrictedServices()->syncWithoutDetaching([$service->id]);
                }
            }
        }

        // Categoria Avançado - Sem restrições
        // Não adiciona nenhuma restrição
    }
}
