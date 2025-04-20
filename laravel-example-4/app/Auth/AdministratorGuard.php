<?php
namespace App\Auth;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Auth\TokenGuard;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\UserProvider;

class AdministratorGuard extends TokenGuard {
    use GuardHelpers;

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Create a new authentication guard.
     *
     * @param  \Illuminate\Contracts\Auth\UserProvider  $provider
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(UserProvider $provider, Request $request)
    {
        $this->request = $request;
        $this->provider = $provider;
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        // If we've already retrieved the user for the current request we can just
        // return it back immediately. We do not want to fetch the user data on
        // every call to this method because that would be tremendously slow.
        if (! is_null($this->user)) {
            return $this->user;
        }

        $user = \Auth::user();
        if (is_null($user) || $user->role !== 'administrator') {
            return null;
        }
        return $this->user = $user;
    }
}
