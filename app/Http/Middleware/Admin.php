<?php

namespace App\Http\Middleware;

use Closure;
use App\Constant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
      
        if(auth()->user() && auth()->user()->role_id == Constant::Admin)
        {
            return $next($request);

        }
        return Redirect::route('login')->withErrors(['message' => 'Unauthorized']);
    }
}
