<?php

namespace App\Services\OpenRouteService\Dto;

class RouteDataDTO
{
    /**
     * @param float $distance Distance of the route in kilometers.
     * @param float $duration Estimated duration of the route in seconds.
     * @param array<array<float>> $coordinates Array of [longitude, latitude] pairs.
     */
    public function __construct(
        public float $distance,
        public float $duration,
        public array $coordinates,
    ) {}

    public static function fromArray(array $feature): self
    {
        $summary = $feature['properties']['summary'];
        $distance = $summary['distance'];
        $duration = $summary['duration'];
        $geometryCoordinates = $feature['geometry']['coordinates'];

        return new self(
            distance: $distance,
            duration: $duration,
            coordinates: $geometryCoordinates,
        );
    }
}
