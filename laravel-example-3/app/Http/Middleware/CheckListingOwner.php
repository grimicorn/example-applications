<?php

namespace App\Http\Middleware;

use Closure;
use App\Listing;

class CheckListingOwner
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
        $id = intval($request->route('id'));
        if ($id !== 0 and !Listing::findOrFail($id)->isOwner()) {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
