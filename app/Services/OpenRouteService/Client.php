<?php

namespace App\Services\OpenRouteService;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class Client
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct(?string $apiKey = null)
    {
        $this->baseUrl = config('openrouteservice.baseUrl');
        $this->apiKey = $apiKey ?? config('openrouteservice.apiKey');
    }

    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    /**
     * GET Request on OpenRouteServiceAPI.
     *
     * @param string $endpoint
     * @param array $params
     * @param string $acceptHeader
     * @return array|mixed
     * @throws ConnectionException
     * @throws RequestException
     */
    public function get(string $endpoint, array $params = [], string $acceptHeader = 'application/json'): mixed
    {
        return Http::withHeaders([
            'Authorization' => $this->apiKey,
            'Accept' => $acceptHeader,
            'Content-Type' => 'application/json',
        ])->get("{$this->baseUrl}/$endpoint", $params)->throw()->json();
    }

    /**
     * POST Request on OpenRouteServiceAPI.
     *
     * @param string $endpoint
     * @param array $data
     * @param string $acceptHeader
     * @return array|mixed
     * @throws ConnectionException
     * @throws RequestException
     */
    public function post(string $endpoint, array $data = [], string $acceptHeader = 'application/json'): mixed
    {
        return Http::withHeaders([
            'Authorization' => $this->apiKey,
            'Accept' => $acceptHeader,
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/$endpoint", $data)->throw()->json();
    }

    /**
     * Test API Key for OpenRouteServiceAPI.
     *
     * @throws ConnectionException|RequestException
     */
    public function testApiKey()
    {
        return $this->get(
            endpoint: '/elevation/point',
            params: ['geometry' => '13.349762,38.11295']
        );
    }
}
