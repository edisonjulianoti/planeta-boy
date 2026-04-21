<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\ProfilePricing;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfilePricingSeeder extends Seeder
{
    public function run(): void
    {
        $profiles = Profile::all();
        $durations = [
            ['15 minutos', 40, 80],
            ['30 minutos', 80, 150],
            ['1 hora', 120, 300],
            ['2 horas', 220, 500],
            ['noite', 400, 800],
        ];

        foreach ($profiles as $profile) {
            $numPricing = rand(2, 4);
            $selectedDurations = fake()->randomElements($durations, $numPricing);

            foreach ($selectedDurations as $duration) {
                ProfilePricing::create([
                    'profile_id' => $profile->id,
                    'duration' => $duration[0],
                    'price' => rand($duration[1], $duration[2]),
                    'description' => fake()->optional(0.3)->sentence(),
                ]);
            }
        }

        $this->command->info('Preços criados com sucesso!');
    }
}
