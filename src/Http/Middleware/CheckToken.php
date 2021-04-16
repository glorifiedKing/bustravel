<?php

namespace glorifiedking\BusTravel\Http\Middleware;

use Closure;
use glorifiedking\BusTravel\ApiKey;
use Illuminate\Support\Facades\Log;

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
            Log::info("check_api_token: NONE");
            return response()->json([
                'status' => 'authentication error',
                'result' => 'missing token'
            ],401);
            
        }
        
        $token = ApiKey::where('token',$request->token)->first();
        if(!$token)
        {  
            Log::info("check_api_token: INVALID TOKEN");          
            return response()->json([
                'status' => 'authentication error',
                'result' => 'invalid'
            ],401);
        }
        // check allowed ip addresses 
        $token_ip_addresses = $token->ip_addresses;
        if(!in_array($request->ip(),$token_ip_addresses))
        {  
            Log::info("check_api_token: INVALID IP");          
            return response()->json([
                'status' => 'authentication error',
                'result' => 'invalid source'
            ],401);
        }        

        return $next($request);
    }
}