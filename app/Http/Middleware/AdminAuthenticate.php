<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class AdminAuthenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('admin.login');
    }
    
    /**
     * Determine if the user is logged in to any of the given guards.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function authenticate($request, array $guards)
    {
        // Force admin guard
        if (empty($guards)) {
            $guards = ['admin'];
        }

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                // Set this guard as the default for this request
                $this->auth->shouldUse($guard);
                return;
            }
        }

        // If no guard is authenticated, throw exception
        $this->unauthenticated($request, $guards);
    }
}