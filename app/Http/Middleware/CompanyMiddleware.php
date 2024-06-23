<?php

namespace App\Http\Middleware;

use App\Models\Company;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompanyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->environment() !== 'local') {
            $subdomain = explode('.', $request->getHost())[0];

            if ($subdomain === 'www') {
                return $next($request);
            }

            if (!empty($subdomain)) {
                $company = Company::where('subdomain', $subdomain)->firstOrFail();

                if ($company) {
                    session(['company' => $company]);
                } else {
                    abort(404, 'Company not found');
                }
            }
        }

        return $next($request);
    }
}
