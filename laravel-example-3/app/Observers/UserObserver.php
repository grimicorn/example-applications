<?php

namespace App\Observers;

use App\User;
use App\Mail\NewNotification;
use App\ExchangeSpaceNotification;
use App\Support\Notification\HasNotifications;
use App\Support\Notification\NewUserNotification;
use App\Support\Notification\ClosedAccountNotification;

class UserObserver
{
    use HasNotifications;

    /**
     * Listen to the User created event.
     *
     * @param  User  $user
     * @return void
     */
    public function created(User $user)
    {
        // Create required relationships.
        $user->desiredPurchaseCriteria()->forceCreate(['user_id' => $user->id]);
        $user->professionalInformation()->forceCreate(['user_id' => $user->id]);
        $user->emailNotificationSettings()->forceCreate(['user_id' => $user->id]);

        // Send the new user email.
        $this->dispatchNotification(new NewUserNotification($user));
    }

    /**
     * Listen to the User deleting event.
     *
     * @param  User  $user
     * @return void
     */
    public function deleting(User $user)
    {
        // Remove the listings
        // Removes listings Business Inquiries and Exchange Spaces via cascade
        // This will handle all Business Inquiries and Exchange Spaces where the user is the seller
        $user->listings->each->delete();

        $user->listings()->withTrashed()->get()->each(function ($listing) {
            $listing->exitSurvey->participant_message = $listing->user_removed_message;
            $listing->exitSurvey->save();
        });

        // Close all of the buyer inquires where the user is a prospective buyer
        // and deactivate them in all other situations

        $user->spaceMembers()->get()->each(function ($member) {
            $space = $member->space()->withTrashed()->first();

            if (!$space->trashed() and ($space->is_inquiry or $member->is_seller)) {
                $space->delete();
            } else {
                $member->deactivate();
            }
        });

        // Remove the watchlists
        $user->savedSearches->each->delete();

        // Remove the favorites
        $user->favorites->each->delete();

        // Delete all of the users notifications.
        $this->deleteUserNotifications($user);

        // Cancel the subscription
        if ($user->isSubscribed() or $user->onGracePeriod()) {
            $user->currentSubscription()->cancelNow();
        }

        // Notifiy who needs to be notified.
        $this->dispatchNotification(new ClosedAccountNotification($user));
    }
}
