<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        app()->setLocale("en");


        if (\auth()->user()->role == "user") {
            return $next($request);
        } else {
            \abort("403", 'انت لست مسوق');
        }
    }
}
