<?php

namespace Database\Factories;

use App\Models\Travel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tour>
 */
class TourFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'travel_id' => Travel::factory()->create()->id,
            'name' => $this->faker->word,
            'starting_date' => $this->faker->date(),
            'ending_date' => $this->faker->date(),
            'price' => 1000,
        ];
    }
}
