<?php

namespace App\Services\OpenRouteService;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class Client
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->baseUrl = config('openrouteservice.baseUrl');
        $this->apiKey = $apiKey;
    }

    /**
     * GET Request on OpenRouteServiceAPI.
     *
     * @param string $endpoint
     * @param array $params
     * @return array|mixed
     * @throws ConnectionException|RequestException
     */
    public function get(string $endpoint, array $params = [])
    {
        return Http::withHeaders([
            'Authorization' => $this->apiKey,
            'Accept' => 'application/json',
        ])->get("{$this->baseUrl}/$endpoint", $params)->throw()->json();
    }

    /**
     * POST Request on OpenRouteServiceAPI.
     *
     * @param string $endpoint
     * @param array $data
     * @return array|mixed
     * @throws ConnectionException|RequestException
     */
    public function post(string $endpoint, array $data = [])
    {
        return Http::withHeaders([
            'Authorization' => $this->apiKey,
            'Accept' => 'application/json',
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
        )->throw();
    }
}
