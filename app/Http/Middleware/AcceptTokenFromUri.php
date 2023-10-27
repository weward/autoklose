<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AcceptTokenFromUri
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('api_token')) {
            $token = $request->input('api_token');
            $token = str_replace("%7C", "|", $token);

            $request->headers->set('Authorization', 'Bearer ' . $token);

            return $next($request);
        }

        abort(403);
    }
}
