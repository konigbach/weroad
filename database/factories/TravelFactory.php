<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Travel>
 */
class TravelFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'is_public' => true,
            'slug' => $this->faker->slug,
            'name' => $this->faker->name,
            'description' => $this->faker->text(10),
            'days' => 10,
            'moods' => [
                'nature' => 100,
                'relax' => 90,
                'history' => 80,
                'culture' => 70,
                'party' => 60,
            ],
        ];
    }
}
