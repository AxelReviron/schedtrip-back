<?php

namespace Database\Factories;

use App\Models\Luggage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'label' => $this->faker->name(),
            'quantity' => $this->faker->numberBetween(1, 3),
            'luggage_id' => Luggage::factory()
        ];
    }
}
