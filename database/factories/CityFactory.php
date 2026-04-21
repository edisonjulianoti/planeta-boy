<?php

namespace Database\Factories;

use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<City>
 */
class CityFactory extends Factory
{
    protected $model = City::class;

    public function definition(): array
    {
        $cidades = [
            ['name' => 'São Paulo', 'state' => 'SP', 'slug' => 'sao-paulo'],
            ['name' => 'Curitiba', 'state' => 'PR', 'slug' => 'curitiba'],
            ['name' => 'Florianópolis', 'state' => 'SC', 'slug' => 'florianopolis'],
            ['name' => 'Joinville', 'state' => 'SC', 'slug' => 'joinville'],
            ['name' => 'Ponta Grossa', 'state' => 'PR', 'slug' => 'ponta-grossa'],
            ['name' => 'Guarulhos', 'state' => 'SP', 'slug' => 'guarulhos'],
            ['name' => 'Londrina', 'state' => 'PR', 'slug' => 'londrina'],
            ['name' => 'Maringá', 'state' => 'PR', 'slug' => 'maringa'],
            ['name' => 'Balneário Camboriú', 'state' => 'SC', 'slug' => 'balneario-camboriu'],
            ['name' => 'São José dos Campos', 'state' => 'SP', 'slug' => 'sao-jose-dos-campos'],
        ];

        $cidade = $this->faker->randomElement($cidades);

        return [
            'name' => $cidade['name'],
            'state' => $cidade['state'],
            'slug' => $cidade['slug'],
            'image' => null,
            'active' => true,
            'order' => $this->faker->numberBetween(1, 10),
        ];
    }
}
