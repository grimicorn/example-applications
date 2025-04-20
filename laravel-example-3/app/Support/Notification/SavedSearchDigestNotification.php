<?php

namespace App\Support\Notification;

use App\User;
use App\SavedSearch;
use Illuminate\Support\Collection;
use App\Support\Notification\Notification;
use App\Support\Notification\NotificationType;
use App\SavedSearchNotification as SavedSearchNotificationModel;

class SavedSearchDigestNotification extends Notification
{
    protected $searches;
    protected $user;

    public function __construct(User $user, $data = [])
    {
        parent::__construct(NotificationType::SAVED_SEARCH_MATCH_DIGEST, $data);
        $this->setRecipient($user);
        $this->user = $user->fresh();
        $this->searches = $this->user->savedSearches;
    }

    /**
     * {@inheritDoc}
     */
    public function save()
    {
        // Notification is not saved
    }

    /**
     * {@inheritDoc}
     */
    public function emailBody()
    {
        $viewSlug = $this->getViewSlugFromType();
        return view("app.sections.notifications.email.{$viewSlug}", [
            'searches' => $this->searches,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function shouldEmail()
    {
        $enable_all = optional($this->recipient()->emailNotificationSettings)->enable_all;
        if (!$enable_all) {
            return false;
        }

        return $this->shouldNotify();
    }

    /**
     * {@inheritDoc}
     */
    public function shouldNotify()
    {
        return !$this->searches->map->hasNewListings()->filter()->isEmpty();
    }

    public function emailSubject()
    {
        return NotificationType::emailSubject($this->type);
    }
}
