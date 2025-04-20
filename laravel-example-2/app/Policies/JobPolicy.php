<?php

namespace App\Policies;

use App\User;
use App\Job;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Enums\UserPermissionEnum;

class JobPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any jobs.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(?User $user)
    {
        return !is_null($user);
    }

    /**
     * Determine whether the user can view the job.
     *
     * @param  \App\User  $user
     * @param  \App\Job  $job
     * @return mixed
     */
    public function view(?User $user, Job $job)
    {
        return !is_null($user);
    }

    /**
     * Determine whether the user can create jobs.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo(UserPermissionEnum::MANAGE_JOBS);
    }

    /**
     * Determine whether the user can update the job.
     *
     * @param  \App\User  $user
     * @param  \App\Job  $job
     * @return mixed
     */
    public function update(User $user, Job $job)
    {
        return $user->hasPermissionTo(UserPermissionEnum::MANAGE_JOBS);
    }

    /**
     * Determine whether the user can delete the job.
     *
     * @param  \App\User  $user
     * @param  \App\Job  $job
     * @return mixed
     */
    public function delete(User $user, Job $job)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the job.
     *
     * @param  \App\User  $user
     * @param  \App\Job  $job
     * @return mixed
     */
    public function restore(User $user, Job $job)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the job.
     *
     * @param  \App\User  $user
     * @param  \App\Job  $job
     * @return mixed
     */
    public function forceDelete(User $user, Job $job)
    {
        return false;
    }
}
