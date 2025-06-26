<?php

namespace App\Services\OpenRouteService\Services;

use App\Services\OpenRouteService\Client;
use App\Services\OpenRouteService\Dto\LocationFeatureDTO;
use App\Services\OpenRouteService\Dto\RouteDataDTO;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;

class Directions
{
    protected Client $client;

    public function __construct(Client $client) {
        $this->client = $client;
    }

    /**
     * Returns a route between two or more locations for a selected profile and its settings as GeoJSON
     *
     * @param array<float> $coordinates
     * @return Collection<LocationFeatureDTO>
     * @throws ConnectionException|RequestException
     */
    public function route(array $coordinates, string $profile = 'driving-car'): Collection
    {
        $response = $this->client->post(
            endpoint: "/v2/directions/$profile/geojson",
            data: [
                'coordinates' => $coordinates,
                'instructions' => false,
                'units' => 'km'
            ],
            acceptHeader: 'application/geo+json'
        );

        return collect($response['features'])->map(fn (array $feature) => RouteDataDTO::fromArray($feature));
    }
}
