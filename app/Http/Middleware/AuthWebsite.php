<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthWebsite
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
      $api_key = env('API_KEY');
      $header = $request->header('Authorization');
      if($api_key !== $header)
      {
        return response()->json([
          'data' => $api_key,
          'status' => 'unauthorized'
        ], 401);
      }
      return $next($request);

    }
}
