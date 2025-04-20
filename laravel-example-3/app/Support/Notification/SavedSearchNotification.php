<?php

namespace App\Support\Notification;

use App\SavedSearch;
use App\Support\Notification\Notification;
use App\Support\Notification\NotificationType;
use App\SavedSearchNotification as SavedSearchNotificationModel;

class SavedSearchNotification extends Notification
{
    protected $savedSearch;
    protected $listing;

    public function __construct(SavedSearch $savedSearch, $listing, $data = [])
    {
        parent::__construct(NotificationType::SAVED_SEARCH_MATCH, $data);
        $this->setRecipient($savedSearch->user);
        $this->savedSearch = $savedSearch;
        $this->listing = $listing;
    }

    /**
     * {@inheritDoc}
     */
    public function save()
    {
        SavedSearchNotificationModel::forceCreate([
            'user_id' => $this->savedSearch->user->id,
            'saved_search_id' => $this->savedSearch->id,
            'type' => $this->type,
            'listing_id' => $this->listing->id,
            'rule_name' => $this->savedSearch->name,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function emailBody()
    {
        // Email does not send
    }

    /**
     * {@inheritDoc}
     */
    public function shouldEmail()
    {
        return false;
    }
}
