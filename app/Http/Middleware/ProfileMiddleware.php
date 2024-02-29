<?php

namespace App\Http\Middleware;

use App\Models\Profile;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProfileMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $profile_id = $request->header('X-Profile-ID');

        $profile = Profile::whereId($profile_id)
            ->whereUserId(auth()->id())
            ->first();

        if (! $profile) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $request->merge(['profile' => $profile]);

        return $next($request);
    }
}
