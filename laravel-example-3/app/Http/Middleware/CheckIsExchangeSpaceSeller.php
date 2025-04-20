<?php

namespace App\Http\Middleware;

use Closure;
use App\ExchangeSpace;

class CheckIsExchangeSpaceSeller
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
        $member = ExchangeSpace::withTrashed()->findOrFail($request->route('id'))
                  ->current_member;
        if (!optional($member)->is_seller) {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
