<?php

namespace App\Http\Middleware;

use Closure;

class CheckSingleSignOnToken
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
        if (!auth()->check() or auth()->user()->isSingleSignOn()) {
            return $next($request);
        }

        auth()->logout();
        return redirect(route('login'));
    }
}
