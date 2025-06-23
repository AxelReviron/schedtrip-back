<?php

namespace App\Http\Controllers;

use App\Services\OpenRouteService\Services\GeoCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OpenRouteServiceController extends Controller
{
    private GeoCode $geoCodeService;

    public function __construct(GeoCode $geoCodeService)
    {
        $this->geoCodeService = $geoCodeService;
    }

    public function searchPlace(string $place): JsonResponse
    {
        $results = $this->geoCodeService->search($place);
        return response()->json($results);
    }

    public function getRoute(Request $request): JsonResponse
    {
        // TODO
    }
}
