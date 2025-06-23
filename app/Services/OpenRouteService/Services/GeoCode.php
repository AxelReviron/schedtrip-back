<?php

namespace App\Services\OpenRouteService\Services;

use App\Services\OpenRouteService\Client;
use App\Services\OpenRouteService\Dto\LocationFeatureDTO;
use Illuminate\Http\Client\ConnectionException;
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
     * @throws ConnectionException
     */
    public function search(string $place): Collection
    {
        $response = $this->client->get(
            endpoint: '/geocode/search',
            params: ['text' => $place]
        );

        return collect($response['features'])->map(fn ($feature) => LocationFeatureDTO::fromArray($feature));
    }
}
