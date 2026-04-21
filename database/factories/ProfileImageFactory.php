<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ProfileImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProfileImage>
 */
class ProfileImageFactory extends Factory
{
    protected $model = ProfileImage::class;

    public function definition(): array
    {
        return [
            'url' => $this->faker->imageUrl(800, 1200),
            'order' => $this->faker->numberBetween(0, 10),
            'is_main' => false,
        ];
    }

    public function main(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_main' => true,
            'order' => 0,
        ]);
    }
}
