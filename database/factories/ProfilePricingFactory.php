<?php

namespace Database\Factories;

use App\Models\ProfilePricing;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProfilePricing>
 */
class ProfilePricingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $durations = ['15 minutos', '30 minutos', '1 hora', '2 horas', 'noite'];

        return [
            'profile_id' => \App\Models\Profile::factory(),
            'duration' => $this->faker->randomElement($durations),
            'price' => $this->faker->numberBetween(40, 500),
            'description' => $this->faker->optional(0.3)->sentence(),
        ];
    }
}
