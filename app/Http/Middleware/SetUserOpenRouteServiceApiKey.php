<?php

namespace App\Http\Middleware;

use App\Services\OpenRouteService\Client as OpenRouteServiceClient;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetUserOpenRouteServiceApiKey
{
    protected OpenRouteServiceClient $openRouteServiceClient;

    public function __construct(OpenRouteServiceClient $openRouteServiceClient)
    {
        $this->openRouteServiceClient = $openRouteServiceClient;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && isset(Auth::user()->ors_api_key)) {
            $userApiKey = Auth::user()->ors_api_key;
            $this->openRouteServiceClient->setApiKey($userApiKey);
        }
        return $next($request);
    }
}
