<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\ProfilePhysicalAttribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfilePhysicalAttributeSeeder extends Seeder
{
    public function run(): void
    {
        $profiles = Profile::all();

        foreach ($profiles as $profile) {
            ProfilePhysicalAttribute::create([
                'profile_id' => $profile->id,
                'height' => rand(150, 190),
                'weight' => rand(50, 90),
                'hair_color' => fake()->randomElement(['preto', 'castanho', 'loiro', 'ruivo', 'grisalho']),
                'eye_color' => fake()->randomElement(['castanho', 'azul', 'verde', 'preto']),
                'ethnicity' => fake()->randomElement(['branca', 'negra', 'parda', 'amarela', 'indígena']),
            ]);
        }

        $this->command->info('Atributos físicos criados com sucesso!');
    }
}
