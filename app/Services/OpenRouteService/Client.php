<?php

namespace App\Services\OpenRouteService;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class Client
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('openrouteservice.baseUrl');
        $this->apiKey = config('openrouteservice.apiKey');
    }

    /**
     * @throws ConnectionException
     */
    public function get(string $endpoint, array $params = [])
    {
        return Http::withHeaders([
            'Authorization' => $this->apiKey,
            'Accept' => 'application/json',
        ])->get("{$this->baseUrl}/$endpoint", $params)->json();
    }

    /**
     * @throws ConnectionException
     */
    public function post(string $endpoint, array $data = [])
    {
        return Http::withHeaders([
            'Authorization' => $this->apiKey,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/$endpoint", $data)->json();
    }
}
