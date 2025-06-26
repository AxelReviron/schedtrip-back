<?php

namespace App\Services\OpenRouteService\Dto;

class LocationFeatureDTO
{
    /**
     * @param string $name
     * @param string $label
     * @param float $longitude
     * @param float $latitude
     * @param string $country
     * @param string $region
     * @param string $locality
     */
    public function __construct(
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
            name: $feature['properties']['name'],
            label: $feature['properties']['label'],
            longitude: $feature['geometry']['coordinates'][0],
            latitude: $feature['geometry']['coordinates'][1],
            country: $feature['properties']['country'],
            region: $feature['properties']['region'],
            locality: $feature['properties']['locality'],
        );
    }
}
