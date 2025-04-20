<?php

namespace App\Policies;

use App\User;
use App\SitePage;
use Illuminate\Auth\Access\HandlesAuthorization;

class SitePagePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the site page.
     *
     * @param  \App\User  $user
     * @param  \App\SitePage  $page
     * @return mixed
     */
    public function update(User $user, SitePage $page)
    {
        return $user->id === $page->site->user_id;
    }

    /**
     * Determine whether the user can delete the site page.
     *
     * @param  \App\User  $user
     * @param  \App\SitePage  $page
     * @return mixed
     */
    public function delete(User $user, SitePage $page)
    {
        return $user->id === $page->site->user_id;
    }
}
