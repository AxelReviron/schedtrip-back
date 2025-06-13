<?php

namespace Database\Factories;

use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stop>
 */
class StopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'label' => $this->faker->city(),
            'description' => $this->faker->text(),
            'trip_id' => Trip::factory(),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'arrival_date' => $this->faker->date(),
            'departure_date' => $this->faker->date(),
            'order_index' => $this->faker->numberBetween(0, 10),
        ];
    }
}
