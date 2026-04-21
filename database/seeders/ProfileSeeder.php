<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        // Buscar serviços ativos
        $services = Service::where('active', true)->get();

        // Buscar usuários não administradores
        $users = User::where('is_admin', false)->get();

        $totalProfiles = 0;
        $maxProfiles = 40;

        // Criar perfis até atingir 40 no total
        foreach ($users as $user) {
            if ($totalProfiles >= $maxProfiles) {
                break;
            }

            $remaining = $maxProfiles - $totalProfiles;
            $numProfiles = rand(1, min(3, $remaining));

            for ($i = 0; $i < $numProfiles; $i++) {
                $profile = Profile::factory()->create([
                    'user_id' => $user->id,
                ]);

                // Atribuir serviços aleatórios se existir
                if ($services->isNotEmpty()) {
                    $numServices = rand(1, min(5, $services->count()));
                    $randomServices = $services->random($numServices);
                    foreach ($randomServices as $service) {
                        $profile->services()->attach($service->id, ['price' => rand(50, 500)]);
                    }
                }

                $totalProfiles++;
            }
        }
    }
}
