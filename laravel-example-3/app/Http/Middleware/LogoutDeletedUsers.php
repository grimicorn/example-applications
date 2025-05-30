<?php

namespace App\Http\Middleware;

use Closure;

class LogoutDeletedUsers
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
        if (optional(auth()->user())->trashed()) {
            auth()->logout();
            return redirect('login');
        }

        return $next($request);
    }
}
