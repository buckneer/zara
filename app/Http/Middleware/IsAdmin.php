<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user || ! $user->isAdmin()) {
            
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Forbidden. Admins only.'], 403);
            }
            abort(403, 'Forbidden — Admins only.');
        }

        return $next($request);
    }
}
