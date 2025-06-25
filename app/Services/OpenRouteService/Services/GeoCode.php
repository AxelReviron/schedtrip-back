<?php

namespace App\Services\OpenRouteService\Services;

use App\Services\OpenRouteService\Client;
use App\Services\OpenRouteService\Dto\LocationFeatureDTO;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;

class GeoCode
{
    protected Client $client;

    public function __construct(Client $client) {
        $this->client = $client;
    }

    /**
     * Resolve address to input coordinates.
     *
     * @param string $place
     * @return Collection<LocationFeatureDTO>
     * @throws ConnectionException|RequestException
     */
    public function search(string $place): Collection
    {
        $response = $this->client->get(
            endpoint: '/geocode/search',
            params: ['text' => $place]
        );

        return collect($response['features'])->map(fn ($feature) => LocationFeatureDTO::fromArray($feature));
    }

    /**
     * Resolve input coordinates into address.
     *
     * @param array<float> $coordinates
     * @return Collection<LocationFeatureDTO>
     * @throws ConnectionException|RequestException
     */
    public function reverseSearch(array $coordinates): Collection
    {
        $response = $this->client->get(
            endpoint: '/geocode/reverse',
            params: [
                'point.lon' => $coordinates['lon'],
                'point.lat' => $coordinates['lat'],
            ]
        );

        return collect($response['features'])->map(fn ($feature) => LocationFeatureDTO::fromArray($feature));
    }
}
