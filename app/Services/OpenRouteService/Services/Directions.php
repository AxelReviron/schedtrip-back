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
     * @param string $profile
     * @return mixed
     * @throws RequestException|ConnectionException
     */
    public function route(array $coordinates, string $profile = 'driving-car'): mixed
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

        return $response;
    }
}
