<?php

namespace App\Policies;

use App\User;
use App\Notification;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotificationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the database notification.
     *
     * @param  \App\User  $user
     * @param  \App\Notification  $notification
     * @return mixed
     */
    public function view(User $user, Notification $notification)
    {
        if ($notification->notifiable_type === User::class) {
            return intval($user->id) === intval($notification->notifiable_id);
        }

        return false;
    }

    /**
     * Determine whether the user can create database notifications.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the database notification.
     *
     * @param  \App\User  $user
     * @param  \App\Notification  $notification
     * @return mixed
     */
    public function update(User $user, Notification $notification)
    {
        if ($notification->notifiable_type === User::class) {
            return intval($user->id) === intval($notification->notifiable_id);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the database notification.
     *
     * @param  \App\User  $user
     * @param  \App\Notification  $notification
     * @return mixed
     */
    public function delete(User $user, Notification $notification)
    {
        if ($notification->notifiable_type === User::class) {
            return intval($user->id) === intval($notification->notifiable_id);
        }

        return false;
    }

    /**
     * Determine whether the user can restore the database notification.
     *
     * @param  \App\User  $user
     * @param  \App\Notification  $notification
     * @return mixed
     */
    public function restore(User $user, Notification $notification)
    {
        if ($notification->notifiable_type === User::class) {
            return intval($user->id) === intval($notification->notifiable_id);
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the database notification.
     *
     * @param  \App\User  $user
     * @param  \App\Notification  $notification
     * @return mixed
     */
    public function forceDelete(User $user, Notification $notification)
    {
        if ($notification->notifiable_type === User::class) {
            return intval($user->id) === intval($notification->notifiable_id);
        }

        return false;
    }
}
