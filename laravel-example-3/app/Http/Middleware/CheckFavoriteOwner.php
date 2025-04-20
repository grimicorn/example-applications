<?php

namespace App\Http\Middleware;

use Closure;
use App\Favorite;

class CheckFavoriteOwner
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
        if (!Favorite::findOrFail($request->route('id'))->isOwner()) {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
