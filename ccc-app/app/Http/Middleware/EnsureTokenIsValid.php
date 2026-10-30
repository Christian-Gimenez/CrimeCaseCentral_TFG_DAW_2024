<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tokenSession = session("token");
        $tokenGet = $request->get("winterhorn");

        if ((!$tokenGet || !$tokenSession) || ($tokenGet != $tokenSession) || ($tokenGet == "" || $tokenSession == "") ) {
            return redirect() -> route("login");
        }
        return $next($request);
    }
}
