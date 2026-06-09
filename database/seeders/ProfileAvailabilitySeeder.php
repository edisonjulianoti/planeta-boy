<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\ProfileAvailability;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfileAvailabilitySeeder extends Seeder
{
    public function run(): void
    {
        $profiles = Profile::all();
        $daysOfWeek = ['segunda', 'terça', 'quarta', 'quinta', 'sexta', 'sábado', 'domingo'];

        foreach ($profiles as $profile) {
            $selectedDays = fake()->randomElements($daysOfWeek, rand(3, 7));

            ProfileAvailability::create([
                'profile_id' => $profile->id,
                'days' => $selectedDays,
                'start_time' => fake()->time('H:i', '18:00'),
                'end_time' => fake()->time('H:i', '23:00'),
            ]);
        }

        $this->command->info('Disponibilidade criada com sucesso!');
    }
}
