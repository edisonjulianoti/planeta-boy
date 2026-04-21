<?php

namespace Database\Factories;

use App\Models\ProfileComment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProfileComment>
 */
class ProfileCommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $comments = [
            'Excelente experiência, recomendo!',
            'Muito profissional e atencioso.',
            'Momentos inesquecíveis.',
            'Perfeito em todos os sentidos.',
            'Superou todas as expectativas.',
            'Atendimento impecável.',
            'Vale cada centavo.',
            'Já voltei várias vezes.',
        ];

        return [
            'profile_id' => \App\Models\Profile::factory(),
            'user_id' => \App\Models\User::factory(),
            'comment' => $this->faker->randomElement($comments),
            'rating' => $this->faker->randomFloat(1, 3, 5),
        ];
    }
}
