<?php

namespace App\Http\Middleware;

use Closure;
use App\ExchangeSpace;

class CheckExchangeSpaceHistoricalFinancialsAccess
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
        $space = ExchangeSpace::findOrFail($request->route('id'));
        if (!$space->canAccessHistoricalFinancials()) {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
