<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Criar usuário administrador
        User::updateOrCreate(
            ['email' => 'admin@email.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin'),
                'is_admin' => true,
                'plan' => 'premium',
                'cpf' => '123.456.789-00',
                'data_nascimento' => '1990-01-01',
            ]
        );

        // Criar 20 usuários de teste
        User::factory(20)->create();
    }
}
