<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthGatesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //$user = auth()->guard('api')->user();
        

        if(auth()->user()) {
            dd(auth()->user()->email);
            return $next($request);
            
        }else{
            dd("error");
        }
        
    }
}
