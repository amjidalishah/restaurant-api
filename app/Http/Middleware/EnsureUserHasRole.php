<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        $allowedRoles = collect($roles)
            ->flatMap(fn ($roleGroup) => explode('|', $roleGroup))
            ->map(fn ($role) => strtolower(trim($role)))
            ->filter()
            ->values();

        if (! $allowedRoles->isEmpty() && ! $allowedRoles->contains(strtolower($user->role))) {
            abort(403);
        }

        return $next($request);
    }
}
