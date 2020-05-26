<?php

namespace Modules\Store\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Store\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\JWTAuth;

class Jwt extends BaseMiddleware
{
    protected $guard = 'store';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try{
            if ($userInfo = Auth::guard($this->guard)->user()) {
                return $next($request);
            }
            return (new Controller)->fail(10101);
        }catch (TokenExpiredException $exception){
            return (new Controller)->fail(10101);
        }
    }
}
