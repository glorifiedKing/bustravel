<?php

namespace glorifiedking\BusTravel\Http\Middleware;

use Closure;
use glorifiedking\BusTravel\ApiKey;

class CheckToken
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
        if(!$request->has('token'))
        {            
            
            return response()->json([
                'status' => 'authentication error',
                'result' => 'missing token'
            ],401);
            
        }
        
        $token = ApiKey::where('token',$request->token)->first();
        if(!$token)
        {            
            return response()->json([
                'status' => 'authentication error',
                'result' => 'invalid'
            ],401);
        }
        // check allowed ip addresses 
        $token_ip_addresses = $token->ip_addresses;
        if(!in_array($request->ip(),$token_ip_addresses))
        {            
            return response()->json([
                'status' => 'authentication error',
                'result' => 'invalid source'
            ],401);
        }        

        return $next($request);
    }
}