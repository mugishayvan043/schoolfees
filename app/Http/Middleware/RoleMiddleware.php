<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $roles = array_slice(func_get_args(), 2);

        if (! $request->user() || ! in_array($request->user()->role, $roles, true)) {
            Log::warning('Unauthorized role access blocked.', [
                'user_id' => optional($request->user())->id,
                'role' => optional($request->user())->role,
                'required_roles' => $roles,
                'url' => $request->fullUrl(),
            ]);
            abort(403, 'You are not authorized to access this page.');
        }

        return $next($request);
    }
}
