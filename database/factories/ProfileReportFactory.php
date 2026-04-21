<?php

namespace Database\Factories;

use App\Models\ProfileReport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProfileReport>
 */
class ProfileReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $reasons = ['conteudo_inapropriado', 'falsa_identidade', 'golpe', 'spam', 'outro'];

        return [
            'profile_id' => \App\Models\Profile::factory(),
            'user_id' => \App\Models\User::factory(),
            'reason' => $this->faker->randomElement($reasons),
            'description' => $this->faker->optional(0.5)->text(),
            'status' => $this->faker->randomElement(['pendente', 'analise', 'resolvido', 'rejeitado']),
        ];
    }
}
