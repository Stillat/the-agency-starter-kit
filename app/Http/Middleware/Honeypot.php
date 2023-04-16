<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Honeypot
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('honeypot') && ! empty($request->input('honeypot'))) {
            // Block the request if the honeypot field is not empty
            return redirect()->back()->with('success', true);
        }

        return $next($request);
    }
}
