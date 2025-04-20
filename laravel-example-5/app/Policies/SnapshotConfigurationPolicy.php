<?php

namespace App\Policies;

use App\User;
use App\SnapshotConfiguration;
use Illuminate\Auth\Access\HandlesAuthorization;

class SnapshotConfigurationPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the snapshot configuration.
     *
     * @param  \App\User  $user
     * @param  \App\SnapshotConfiguration  $snapshotConfiguration
     * @return mixed
     */
    public function view(User $user, SnapshotConfiguration $snapshotConfiguration)
    {
        return (int) $user->id === (int) $snapshotConfiguration->user->id;
    }

    /**
     * Determine whether the user can update the snapshot configuration.
     *
     * @param  \App\User  $user
     * @param  \App\SnapshotConfiguration  $snapshotConfiguration
     * @return mixed
     */
    public function update(User $user, SnapshotConfiguration $snapshotConfiguration)
    {
        return (int) $user->id === (int) $snapshotConfiguration->user->id;
    }
}
