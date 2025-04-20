<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Laravel\Spark\Spark;

class CheckCanAccessProfile
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
        if (!auth()->check()) {
            return redirect(route('login'));
        }

        // "Developers" always have access.
        if (auth()->user()->isDeveloper()) {
            return $next($request);
        }

        // We should only allow users to view their own balance.
        $user = User::findOrFail($request->route('id'));
        if (auth()->id() !== $user->id) {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
