<?php

namespace App\Http\Middleware;


use App\Http\Traits\GeneralTrait;
use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AssignGuard
{
    use GeneralTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ($guard != null) {

            auth()->shouldUse($guard); //shoud you user guard / table
            $token = $request->header('auth-token');
            $request->headers->set('auth-token', (string) $token, true);
            $request->headers->set('Authorization', 'Bearer ' . $token, true);
            try {

                $user = JWTAuth::parseToken()->authenticate();
                if ($user == null) {
                    return  $this->responseErrorMessage(401, 'Unauthenticated user');
                }
            } catch (TokenExpiredException $e) {
                return  $this->responseErrorMessage(401, 'Unauthenticated user');
            } catch (JWTException $e) {
                return  $this->responseErrorMessage(404, 'token_invalid.' . $e->getMessage());
            }
        }
        return $next($request);
    }
}
