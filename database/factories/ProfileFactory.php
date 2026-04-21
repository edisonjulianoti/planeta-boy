<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Profile>
 */
class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    public function definition(): array
    {
        $firstName = $this->faker->firstName();
        $lastName = $this->faker->lastName();

        $descriptions = [
            'Aventureiro urbano, café e boa conversa.',
            'Trilhas, música ao vivo e autenticidade.',
            'Designer que ama cozinhar nos fins de semana.',
            'Praia, corrida e projetos paralelos.',
            'Leitor, gamer e entusiasta de café especial.',
            'Voluntariado, pets e vida saudável.',
            'Fotógrafo amador e amante de viagens.',
            'Músico, ciclista e apreciador de arte.',
            'Tech lover, café e código aberto.',
            'Yoga, meditação e conexões verdadeiras.',
        ];

        $taglines = [
            'Experiência única garantida',
            'Momentos inesquecíveis',
            'Paixão pelo que faço',
            'Sua melhor companhia',
            'Aventura awaits',
        ];

        $city = \App\Models\City::inRandomOrder()->first();

        return [
            'user_id' => \App\Models\User::factory(),
            'name' => "{$firstName} {$lastName}",
            'age' => $this->faker->numberBetween(18, 45),
            'gender' => $this->faker->randomElement(['masculino', 'feminino', 'trans', 'outros']),
            'city' => $city ? $city->name : 'São Paulo',
            'state' => $city ? $city->state : 'SP',
            'description' => $this->faker->randomElement($descriptions),
            'telegram' => $this->faker->userName(),
            'tagline' => $this->faker->randomElement($taglines),
            'attendance_target' => $this->faker->randomElements(['homens', 'mulheres', 'casais', 'trans'], rand(1, 4)),
            'payment_methods' => $this->faker->randomElements(['pix', 'dinheiro', 'cartao'], rand(1, 3)),
            'documents_verified' => $this->faker->boolean(20),
            'no_reports' => $this->faker->boolean(70),
            'clean_history' => $this->faker->boolean(60),
            'verified' => $this->faker->boolean(30),
            'rating' => $this->faker->randomFloat(1, 3, 5),
            'views' => $this->faker->numberBetween(0, 1000),
            'active' => true,
        ];
    }
}
