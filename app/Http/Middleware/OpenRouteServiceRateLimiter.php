<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class OpenRouteServiceRateLimiter
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     * @throws Exception
     */
    public function handle(Request $request, Closure $next, string $limiterName): Response
    {
        $user = Auth::user();
        $identifier = $user->getKey();
        $hasOwnApiKey = isset($user->ors_api_key);

        if ($hasOwnApiKey) {
            $config = config('openrouteservice.rate_limiter_with_api_key.' . $limiterName);
        } else {
            $config = config('openrouteservice.rate_limiter_without_api_key.' . $limiterName);
        }

        if (!$config) {
            throw new Exception("Rate limiter $limiterName is not configured in OpenRouteService config file.");
        }

        if (!isset($config['per_day']) || !isset($config['per_minute'])) {
            throw new Exception("$limiterName is misconfigured in OpenRouteService config file.");
        }

        $maxAttemptsDay = $config['per_day']['max_attempts'];
        $keyDay = "{$limiterName}-per-day:{$identifier}";
        $maxAttemptsMinute = $config['per_minute']['max_attempts'];
        $keyMinute = "{$limiterName}-per-minute:{$identifier}";

        if (RateLimiter::tooManyAttempts($keyDay, $maxAttemptsDay)) {
            return $this->handleTooManyAttemptsResponse($keyDay, $maxAttemptsDay, 'Daily');
        }

        if (RateLimiter::tooManyAttempts($keyMinute, $maxAttemptsMinute)) {
            return $this->handleTooManyAttemptsResponse($keyMinute, $maxAttemptsMinute, 'Min');
        }

        $decaySecondsMinute = $config['per_minute']['decay_seconds'];
        $decaySecondsDay = $config['per_day']['decay_seconds'];

        RateLimiter::hit($keyMinute, $decaySecondsMinute);
        RateLimiter::hit($keyDay, $decaySecondsDay);

        $response = $next($request);

        $this->handleRateLimiterRemainingHeaders($response, $keyMinute, $maxAttemptsMinute, 'Min');
        $this->handleRateLimiterRemainingHeaders($response, $keyDay, $maxAttemptsDay, 'Daily');

        return $response;
    }

    private function handleTooManyAttemptsResponse(string $key, int $maxAttempts, string $rateLimitType): Response
    {
        $secondsRemaining = RateLimiter::availableIn($key);

        return response('Too many requests.', Response::HTTP_TOO_MANY_REQUESTS)
            ->withHeaders([
                'Retry-After' => $secondsRemaining,
                "X-RateLimit-$rateLimitType-Limit" => $maxAttempts,
                "X-RateLimit-$rateLimitType-Remaining" => 0,
            ]);
    }

    private function handleRateLimiterRemainingHeaders(&$response, string $key, int $maxAttempts, string $rateLimitType): void
    {
        $remaining = RateLimiter::remaining($key, $maxAttempts);
        $response->headers->set("X-RateLimit-$rateLimitType-Limit", $maxAttempts);
        $response->headers->set("X-RateLimit-$rateLimitType-Remaining", $remaining);
    }

}
