<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException as tokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException as tokenInvalidException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class ApiMiddleware extends BaseMiddleware
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
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof tokenInvalidException) {
                $response['message'] = 'Invalid Token';
                return response()->json(['error' => $response], 400);
            } else if ($e instanceof tokenExpiredException) {
                $response['message'] = 'Token is Expired';
                return response()->json(['error' => $response], 400);
            } else {
                return response()->json(['error' => ['message' => 'Authorization Token not Found']]);
            }
        }
        return $next($request);
    }
    
}
