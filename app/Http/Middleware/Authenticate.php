<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request; // Keep for type hinting the argument inside the method

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        // This is the CRITICAL change to stop the 302 Found redirect for SPAs.
        // If the request is AJAX or expects JSON, we return null, which forces
        // Laravel to throw an AuthenticationException and return a 401 response.
        if ($request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return null;
        }

        // Otherwise, redirect to the login page as normal for web browsers.
        return route('login');
    }
}
