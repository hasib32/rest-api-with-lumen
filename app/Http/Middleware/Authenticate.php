<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as Auth;
use Laravel\Passport\Http\Middleware\CheckClientCredentials;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // First, check if the access_token created by the password grant is valid
        if ($this->auth->guard($guard)->guest()) {

            // Then check, access_token created by the client_credentials grant is valid.
            // We need this checking because we could use either password grant or client_credentials grant.
            try {
                app(CheckClientCredentials::class)->handle($request, function(){});
            } catch (AuthenticationException $e) {
                return response()->json((['status' => 401, 'message' => 'Unauthorized']), 401);
            }
        }

        return $next($request);
    }
}
