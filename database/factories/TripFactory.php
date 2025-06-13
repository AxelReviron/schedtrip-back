<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trip>
 */
class TripFactory extends Factory
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
            'description' => $this->faker->text(),
            'distance' => $this->faker->numberBetween(1000, 10000),
            'duration' => $this->faker->numberBetween(1000, 10000),
            'is_public' => $this->faker->boolean(),
            'geojson' => $this->generateFakeGeoJson(),
            'author_id' => User::factory(),
        ];
    }

    private function generateFakeGeoJson(): string
    {
        $points = [];
        $startLat = $this->faker->latitude(43, 50);
        $startLng = $this->faker->longitude(-5, 8);

        for ($i = 0; $i < $this->faker->numberBetween(3, 5); $i++) {
            $points[] = [
                $startLng + ($i * 0.5) + $this->faker->randomFloat(2, -0.2, 0.2),
                $startLat + ($i * 0.3) + $this->faker->randomFloat(2, -0.2, 0.2)
            ];
        }

        $geoJson = [
            'type' => 'FeatureCollection',
            'features' => [
                [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'LineString',
                        'coordinates' => $points
                    ],
                    'properties' => [
                        'name' => $this->faker->city() . ' - ' . $this->faker->city()
                    ]
                ]
            ]
        ];

        return json_encode($geoJson);
    }
}
