<?php

namespace App\Http\Middleware;

use Closure;
use Laravel\Spark\Spark;

class Developer
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
        $user = optional(auth()->user());
        if (!$user->isDeveloper() and !$user->isImpersonatorDeveloper()) {
            abort(403);
        }

        return $next($request);
    }
}
