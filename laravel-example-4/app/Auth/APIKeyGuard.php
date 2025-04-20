<?php
namespace App\Auth;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Auth\TokenGuard;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\UserProvider;

class APIKeyGuard extends TokenGuard {
    use GuardHelpers;

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * The name of the field on the request containing the API token.
     *
     * @var string
     */
    protected $inputKey;

    /**
     * The name of the token "column" in persistent storage.
     *
     * @var string
     */
    protected $storageKey;

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
        $this->inputKey = 'api_token';
        $this->storageKey = 'api_token';
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
        $user = null;
        $token = $this->getTokenForRequest();

        if (! empty($token)) {
            $user = User::where($this->storageKey, $token)->get()->first();
        }

        return $this->user = $user;
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
    }

    /**
     * Get the token for the current request.
     *
     * @return string
     */
    public function getTokenForRequest()
    {
        $content = json_decode($this->request->getContent());
        if (!isset($content->token)) {
            return null;
        }

        return $content->token;
    }
}
