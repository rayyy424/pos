<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // return $next($request)
        //     ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, PATCH, DELETE')
        //     ->header('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, Origin')
        //     // ->header('Content-Type', 'application/json')
        //     ->header('Access-Control-Allow-Origin' , '*');
        $response = $next($request);

        $response->headers->set('Access-Control-Allow-Origin' , '*');
        // header('Access-Control-Allow-Origin:  http://localhost:8100');
        // header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Authorization, Origin');
        // header('Access-Control-Allow-Methods:  POST, PUT');

        $response->headers->set('Access-Control-Allow-Methods', 'GET, HEAD, POST, PUT, DELETE, CONNECT, OPTIONS, TRACE, PATCH');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With,  X-Auth-Token, Application, Origin');

        return $response;
    }
}
