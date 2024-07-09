<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plant>
 */
class PlantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'common_name' => $this->faker->word,
            'watering_general_benchmark' => json_encode([
                'value' => $this->faker->numberBetween(5, 7),
                'unit' => 'days',
            ]),
        ];
    }
}
