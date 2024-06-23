<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class QuiQuoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = env('QUIQUO_TOKEN');
        if(isset($request->token) && (md5($token) === $request->token))
        {
            return $next($request);
        }
        return abort(403, 'Unauthorized. Missing API key.');
    }
}
