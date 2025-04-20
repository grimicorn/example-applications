<?php

namespace App\Http\Middleware;

use Closure;
use App\Conversation;
use App\ExchangeSpace;

class HandleClosedExchangeSpace
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
        // Find the correct space
        if ($request->route('c_id')) {
            $space = optional(Conversation::findOrFail($request->route('c_id'))->space);
        } else {
            $space = optional(ExchangeSpace::withTrashed()->find($request->route('id')));
        }

        $member = optional($space->current_member);

        // We want to allow developers to access conversations and inquiries but not exchange spaces
        if (($request->route('c_id') or $space->is_inquiry) and auth()->user()->isDeveloper()) {
            return $next($request);
        }

        // Still restrict access to members
        if (!$member->canAccessClosedSpace()) {
            abort(403, 'Forbidden');
        }

        // Redirect if the space is "closed" for the current user
        // and we are not on the closed page.
        if ($space->shouldRedirectClosed()) {
            return redirect($space->closedUrl());
        }

        return $next($request);
    }
}
