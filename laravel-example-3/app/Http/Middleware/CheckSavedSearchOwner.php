<?php

namespace App\Http\Middleware;

use Closure;
use App\SavedSearch;

class CheckSavedSearchOwner
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
        if (!SavedSearch::findOrFail($request->route('id'))->isOwner()) {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
