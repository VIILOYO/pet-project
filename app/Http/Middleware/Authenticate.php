<?php

namespace App\Http\Middleware;

use App\Exceptions\Auth\NoAuthException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        if ($request->hasCookie(config("auth.cookie_key"))) {
            $token = $request->cookies->get(config("auth.cookie_key"));
            $request->headers->set("Authorization", "Bearer {$token}");
        }

        $this->authenticate($request, $guards);

        return $next($request);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  Request  $request
     * @return string|null
     */
    protected function redirectTo(Request $request): string|null
    {
        throw new NoAuthException();
    }
}
