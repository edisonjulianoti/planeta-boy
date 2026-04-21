<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Database\Seeder;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'João Silva',
            'email' => 'joao.silva@teste.com',
            'password' => bcrypt('12345678'),
            'phone' => '(11) 99999-9999',
            'bio' => 'Usuário de teste para geolocalização',
            'plan' => 'free',
            'is_admin' => false,
            'blocked' => false,
        ]);

        $perfil = $user->profile()->create([
            'name' => 'João Escort',
            'age' => 25,
            'city' => 'São Paulo',
            'state' => 'SP',
            'description' => 'Acompanante profissional disponível para encontros.',
            'active' => true,
            'verified' => false,
            'rating' => 4.5,
            'views' => 0,
            'latitude' => -23.5505,
            'longitude' => -46.6333,
            'location_enabled' => true,
        ]);

        $this->command->info('Usuário criado: ID ' . $user->id);
        $this->command->info('Perfil criado: ID ' . $perfil->id);
    }
}
