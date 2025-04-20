<?php

namespace App\Http\Middleware;

use Closure;
use App\Conversation;

class CheckIsConversationMember
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
        if (auth()->user()->isDeveloper()) {
            return $next($request);
        }

        $space = optional(Conversation::findOrFail($request->route('c_id'))->space);
        $member = optional($space->current_member);

        if (!$member->canAccessSpace() and !auth()->user()->isDeveloper()) {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
