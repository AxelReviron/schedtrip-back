<?php

namespace App\Services\OpenRouteService\Dto;

class LocationFeatureDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $label,
        public float $longitude,
        public float $latitude,
        public string $country,
        public string $region,
        public string $locality,
    ) {}

    public static function fromArray(array $feature): self
    {
        return new self(
            id: $feature['properties']['id'] ?? '',
            name: $feature['properties']['name'] ?? '',
            label: $feature['properties']['label'] ?? '',
            longitude: $feature['geometry']['coordinates'][0] ?? 0.0,
            latitude: $feature['geometry']['coordinates'][1] ?? 0.0,
            country: $feature['properties']['country'] ?? '',
            region: $feature['properties']['region'] ?? '',
            locality: $feature['properties']['locality'] ?? '',
        );
    }
}
