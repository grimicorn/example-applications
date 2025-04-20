<?php

namespace App\Http\Middleware;

use Closure;
use App\Listing;

class ListingPublishAccess
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
        if (!Listing::findOrFail($request->route('id'))->published) {
            abort(404);
        }

        return $next($request);
    }
}
