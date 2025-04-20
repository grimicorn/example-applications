<?php

namespace App\Support\Styleguide;

use App\User;
use App\Listing;
use App\SavedSearch;
use App\Conversation;
use App\ExchangeSpace;
use App\Support\Notification\NotificationType;
use App\Support\Notification\NotificationTypeGroup;

class Emails
{
    protected $models;
    protected $extraData;

    public function models()
    {
        if ($this->models) {
            return $this->models;
        }

        // Allow of for setting of the user id
        if (request()->get('user_id')) {
            $user = User::findOrFail(request()->get('user_id'));
        } else {
            $user = optional(User::inRandomOrder()->take(1)->first());
        }

        // Allow of for setting of the listing id
        if (request()->get('listing_id')) {
            $listing = Listing::withTrashed()->findOrFail(request()->get('listing_id'));
        } else {
            $listing = optional(Listing::inRandomOrder()->take(1)->first());
        }

        // Allow for setting of the exchange space
        if (request()->get('space_id')) {
            $space = ExchangeSpace::withTrashed()->findOrFail(request()->get('space_id'));
        } else {
            $space = optional(ExchangeSpace::inRandomOrder()->take(1)->first());
        }

        // Allow for setting of the conversation
        if (request()->get('conversation_id')) {
            $conversation = Conversation::findOrFail(request()->get('conversation_id'));
        } else {
            $conversation = optional(Conversation::inRandomOrder()->take(1)->first());
        }

        // Allow for setting of the conversation
        if (request()->get('saved_search_id')) {
            $search = SavedSearch::findOrFail(request()->get('saved_search_id'));
        } else {
            $search = optional(SavedSearch::inRandomOrder()->take(1)->first());
        }

        return collect([
            'user' => $user,
            'listing' => $listing,
            'professional' => $user,
            'space' => $space,
            'conversation' => $conversation,
            'search' => $search,
        ]);
    }

    public function getNotification($type)
    {
        if (is_null($type)) {
            return [];
        }

        return (collect($this->getNotifications())->where('type', $type)->first()) ?? [];
    }

    public function getNotifications()
    {
        $models = $this->models();
        $user = $models->get('user');
        $space = $models->get('space');
        $conversation = $models->get('conversation');
        $search = $models->get('search');

        return NotificationType::getSlugs()
        ->map(function ($slug, $type) use ($user, $space, $conversation, $search) {
            return [
                'slug' => $slug,
                'type' => $type,
                'id' => collect([
                    NotificationTypeGroup::NO_MODEL => $user->id,
                    NotificationTypeGroup::CONVERSATION => $conversation->id,
                    NotificationTypeGroup::EXCHANGE_SPACE => $space->id,
                    NotificationTypeGroup::SAVED_SEARCH => $search->id,
                ])->get(NotificationType::getGroupKey($type)),
            ];
        })
        ->reject(function ($item) {
            // Saved search matches do not send an email but they do send a notification.
            // The email is the NotificationType::SAVED_SEARCH_MATCH_DIGEST
            return $item['type'] === NotificationType::SAVED_SEARCH_MATCH;
        })
        ->map(function ($notification, $type) {
            $notification['title'] = title_case(str_replace(['-', '/'], ' ', $notification['slug']));
            $notification['file_path'] = $notification['slug'];
            $notification['view_path'] = "views/app/sections/notifications/email/{$notification['file_path']}.blade.php";
            $notification['route'] = $this->route($notification);

            return $notification;
        })
        ->toArray();
    }

    public function extraData()
    {
        if ($this->extraData) {
            return $extraData;
        }

        return collect(request()->all())->except([
            'user_id',
            'space_id',
            'conversation_id',
            'saved_search_id',
        ])->filter();
    }

    protected function route($notification)
    {
        $data = $this->extraData()
        ->merge($notification)
        ->except([
            'title',
            'file_path',
            'view_path',
            'route',
            'type',
        ])
        ->toArray();

        $data['slug'] = str_replace('/', '-', $notification['slug']);

        return route('mailable.notification', $data);
    }
}
