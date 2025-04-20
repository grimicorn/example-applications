<?php

namespace App\Http\Middleware;

use Closure;
use App\ExchangeSpace;

class CheckIsExchangeSpaceMember
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
        $space = ExchangeSpace::withTrashed()
        ->findOrFail($request->route('id'));

        // Allow developers to access buyer inquires
        if (auth()->user()->isDeveloper() and $space->is_inquiry) {
            return $next($request);
        }

        $member = $space->current_member;

        if (!optional($member)->canAccessSpace()) {
            abort(403, 'Forbidden');
        }


        return $next($request);
    }
}
