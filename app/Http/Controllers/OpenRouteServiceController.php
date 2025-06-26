<?php

namespace App\Http\Controllers;

use App\Http\Requests\OpenRouteService\GetRouteRequest;
use App\Http\Requests\OpenRouteService\ReverseSearchRequest;
use App\Http\Requests\OpenRouteService\SearchPlaceRequest;
use App\Services\OpenRouteService\Services\Directions;
use App\Services\OpenRouteService\Services\GeoCode;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;

class OpenRouteServiceController extends Controller
{
    private GeoCode $geoCodeService;
    private Directions $directionsService;

    public function __construct(GeoCode $geoCodeService, Directions $directionsService)
    {
        $this->geoCodeService = $geoCodeService;
        $this->directionsService = $directionsService;
    }

    /**
     * Search place by name.
     *
     * @param SearchPlaceRequest $request
     * @return JsonResponse
     * @throws ConnectionException|RequestException
     */
    public function searchPlace(SearchPlaceRequest $request): JsonResponse
    {
        $place = $request->validated()['place'];
        $results = $this->geoCodeService->search($place);
        return response()->json($results);
    }

    /**
     * Search place by coordinates.
     *
     * @param ReverseSearchRequest $request
     * @return JsonResponse
     * @throws ConnectionException|RequestException
     */
    public function reverseSearchPlace(ReverseSearchRequest $request): JsonResponse
    {
        $coordinates = [
            'lon' => $request->validated()['lon'],
            'lat' => $request->validated()['lat']
        ];
        $results = $this->geoCodeService->reverseSearch($coordinates);
        return response()->json($results);
    }

    /**
     * Get route between multiple coordinates.
     *
     * @param GetRouteRequest $request
     * @return JsonResponse
     * @throws ConnectionException|RequestException
     */
    public function getRoute(GetRouteRequest $request): JsonResponse
    {
        $coordinates = $request->validated()['coordinates'];
        $results = $this->directionsService->route($coordinates);
        return response()->json($results);
    }
}
