<?php
namespace App\Support\Notification;

use App\User;
use App\SavedSearch;
use Illuminate\Support\Collection;
use App\Support\Notification\SavedSearchNotification;
use App\Support\Notification\SavedSearchDigestNotification;
use App\SavedSearchNotification as SavedSearchNotificationModel;

trait HasSavedSearchNotifications
{
    protected function getSavedSearchShowUrl()
    {
        return $this->show_url;
    }

    public function dispatchSavedSearchNotification($savedSearch, $listing)
    {
        // Only send if the listing exists in the found ids.
        // Since notifications should only be sent for published updated listings.
        $listingId = optional($listing)->id;
        $hasListing = $savedSearch->listings->pluck('id')->contains($listingId);
        if (!$hasListing or !optional($listing)->published) {
            return;
        }

        $savedSearch = $savedSearch->fresh();
        $notification = new SavedSearchNotification($savedSearch, $listing);
        $this->dispatchNotification($notification);
    }

    public function dispatchSavedSearchDigestNotification(User $user)
    {
        $this->dispatchNotification(
            new SavedSearchDigestNotification($user)
        );
    }

    /**
     * Gets user saved search notifications.
     *
     * @param integer $user_id
     * @return \Illuminate\Support\Collection
     */
    protected function getSavedSearchNotifications($user_id = null)
    {
        $user_id = is_null($user_id) ? auth()->id() : $user_id;
        return SavedSearchNotificationModel::where('user_id', $user_id)->get();
    }
}
