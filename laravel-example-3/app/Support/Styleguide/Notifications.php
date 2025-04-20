<?php

namespace App\Support\Styleguide;

use App\User;
use App\Listing;
use App\SavedSearch;
use App\Conversation;
use App\ExchangeSpace;
use App\ExchangeSpaceMember;
use App\SavedSearchNotification;
use App\ConversationNotification;
use App\ExchangeSpaceNotification;
use Illuminate\Support\Facades\Cache;
use App\Support\ExchangeSpaceDealType;
use App\Support\ExchangeSpaceStatusType;
use App\Support\Notification\HasNotifications;
use App\Support\Notification\NotificationType;

class Notifications
{
    use HasNotifications;

    protected $models;
    protected $noFallback;

    public function __construct($noFallback = false)
    {
        $this->noFallback = $noFallback;
    }

    public function getAll()
    {
        $notifications = collect([]);

        // Saved Search Match
        $notifications->push($this->savedSearchNotification());

        // Exchange Space
        collect($this->getSpaceNotificationTypes())
        ->each(function ($type) use ($notifications) {
            $notifications->push($this->spaceNotification($type));
        });

        // Conversation Types
        collect($this->getConversationNotificationTypes())
        ->each(function ($type) use ($notifications) {
            $notifications->push($this->conversationNotification($type));
        });

        return collect([
            'notifications' => $notifications,
            'ids' => $this->modelIds(),
        ]);
    }

    public function getIndividual()
    {
        $type = intval(request()->get('type'));
        if (!$type) {
            return null;
        }

        // Search
        if ($type === NotificationType::SAVED_SEARCH_MATCH) {
            return $this->savedSearchNotification();
        }

        // Exchange Space
        $spaceTypes = collect($this->getSpaceNotificationTypes())->map('intval');
        if ($spaceTypes->contains($type)) {
            return $this->spaceNotification($type);
        }

        // Conversation
        $conversationTypes = collect($this->getConversationNotificationTypes())->map('intval');
        if ($conversationTypes->contains($type)) {
            return $this->conversationNotification($type);
        }
    }

    protected function savedSearchNotification()
    {
        $search = $this->models()->get('search');
        $listing = $this->models()->get('listing');
        if ($search->listings->where('id', $listing->id)->isEmpty()) {
            $listing = optional($search->listings->random());
        }

        return (new SavedSearchNotification)->forceFill([
            'created_at' => $search->created_at ? : now(),
            'updated_at' => $search->updated_at ? : now(),
            'user_id' => $search->user_id ?: auth()->id(),
            'saved_search_id' => $search->id,
            'listing_id' => $listing->id,
            'rule_name' => $search->name ? : 'Rule Name',
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
    }

    protected function spaceNotification($type)
    {
        $space = $this->models()->get('space');
        $requested_member = $this->models()->get('requested_member');
        $removed_member = $this->models()->get('removed_member');

        return (new ExchangeSpaceNotification)->forceFill([
            'created_at' => $space->created_at ? : now(),
            'updated_at' => $space->updated_at ? : now(),
            'user_id' => request()->get('recipient_user_id') ?? auth()->id(),
            'exchange_space_id' => $space->id,
            'type' => $type,
            'exchange_space_title' => $space->title ? : 'Exchange Space Title',
            'exchange_space_deal' => request()->get('deal') ?? $space->status,
            'exchange_space_status' => ExchangeSpaceStatusType::ACCEPTED,
            'buyer_name' => optional($space->buyerUser())->name ? : 'Buyer Name',
            'business_name' => optional($space->listing)->title ? : 'Business Name',
            'requested_member_name' => optional($requested_member->user)->name ? : 'Requested Member Name',
            'requested_member_id' => $requested_member->id,
            'removed_member_name' => optional($removed_member->user)->name ? : 'Removed Member Name',
            'removed_member_id' => $removed_member->id,
        ]);
    }

    protected function conversationNotification($type)
    {
        $conversation = optional($this->models()->get('conversation'));
        $recipent_id = $this->modelIds()->get('recipient_user_id');
        $sender = optional($conversation->space->members()->where('user_id', '!=', $recipent_id)->inRandomOrder()->take(1)->first());
        $recipent_member = optional($conversation->space->members()->where('user_id', $recipent_id)->take(1)->first());

        return (new ConversationNotification)->forceFill([
            'created_at' => $conversation->created_at ?? now(),
            'updated_at' => $conversation->updated_at ?? now(),
            'user_id' => request()->get('recipient_user_id') ?? auth()->id(),
            'conversation_id' => $conversation->id,
            'type' => $type,
            'message_sender_name' => optional($sender->user)->name,
            'conversation_title' => $conversation->title ?? 'Conversation Title',
            'exchange_space_title' => optional($recipent_member)->custom_title ?? 'Exchange Space Title',
        ]);
    }

    public function modelIds()
    {
        extract($this->models()->toArray());

        return collect([
            'user_id' => $user->id,
            'saved_search_id' => $search->id,
            'space_id' => $space->id,
            'conversation_id' => $conversation->id,
            'requested_user_id' => optional($requested_member->user)->id,
            'removed_user_id' => optional($removed_member->user)->id,
            'conversation_member_user_id' => optional($conversation_member->user)->id,
            'recipient_user_id' => request()->get('recipient_user_id'),
            'requester_user_id' => request()->get('requester_user_id'),
        ]);
    }

    protected function clearModelsCache()
    {
        if (request()->has('clear_cache')) {
            Cache::forget('styleguide_notification_user');
            Cache::forget('styleguide_notification_search');
            Cache::forget('styleguide_notification_space');
            Cache::forget('styleguide_notification_conversation');
            Cache::forget('styleguide_notification_requested_member');
            Cache::forget('styleguide_notification_removed_member');
            Cache::forget('styleguide_notification_conversation_member');
        }
    }

    protected function models()
    {
        if ($this->models) {
            return $this->models;
        }

        // Allow for prematurely busting the random model cache.
        $this->clearModelsCache();

        // Set user id.
        if (request()->has('user_id')) {
            $user = optional(User::find(request()->get('user_id')));
        } else {
            $user = Cache::remember('styleguide_notification_user', 5, function () {
                return optional(User::inRandomOrder()->take(1)->first());
            });
        }

        // Set listing id.
        if (request()->has('listing_id')) {
            $listing = optional(Listing::find(request()->get('listing_id')));
        } else {
            $listing = $this->noFallback ? optional(null) : Cache::remember('styleguide_notification_listing', 5, function () {
                return optional(Listing::inRandomOrder()->take(1)->first());
            });
        }

        // Set saved search id.
        if (request()->has('saved_search_id')) {
            $search = optional(SavedSearch::find(request()->get('saved_search_id')));
        } else {
            $search = $this->noFallback ? optional(null) : Cache::remember('styleguide_notification_search', 5, function () {
                return optional(SavedSearch::inRandomOrder()->take(1)->first());
            });
        }

        // Set exchange space id.
        if (request()->has('space_id')) {
            $space = optional(
                ExchangeSpace::where('id', request()->get('space_id'))->withTrashed()->first()
            );
        } else {
            $space = $this->noFallback ? optional(null) : Cache::remember('styleguide_notification_space', 5, function () {
                return optional(ExchangeSpace::inRandomOrder()->take(1)->first());
            });
        }

        // Set conversation id.
        if (request()->has('conversation_id')) {
            $conversation = optional(Conversation::find(request()->get('conversation_id')));
        } else {
            $conversation = $this->noFallback ? optional(null) : Cache::remember('styleguide_notification_conversation', 5, function () {
                return optional(Conversation::inRandomOrder()->take(1)->first());
            });
        }

        // Set requested member user id.
        if (request()->has('requested_user_id')) {
            $requested_member = optional(
                ExchangeSpaceMember::where('exchange_space_id', optional($space)->id)
                ->withTrashed()
                ->where('user_id', request()->get('requested_user_id'))->first()
            );
        } else {
            $requested_member = $this->noFallback ? optional(null) : Cache::remember('styleguide_notification_requested_member', 5, function () use ($space) {
                return optional(
                    ExchangeSpaceMember::inRandomOrder()->where('exchange_space_id', optional($space)->id)
                        ->take(1)->first()
                );
            });
        }

        // Set removed member user id.
        if (request()->has('removed_user_id')) {
            $removed_member = optional(
                ExchangeSpaceMember::where('exchange_space_id', optional($space)->id)
                ->withTrashed()
                ->where('user_id', request()->get('removed_user_id'))->first()
            );
        } else {
            $removed_member = $this->noFallback ? optional(null) : Cache::remember('styleguide_notification_removed_member', 5, function () use ($space) {
                return optional(
                    ExchangeSpaceMember::inRandomOrder()->where('exchange_space_id', optional($space)->id)
                        ->take(1)->first()
                );
            });
        }


        // Set conversation member user id.
        if (request()->has('conversation_member_user_id')) {
            $conversation_member = optional(
                ExchangeSpaceMember::where('exchange_space_id', optional($conversation->space)->id)
                    ->where('user_id', request()->get('conversation_member_user_id'))->first()
            );
        } else {
            $conversation_member = $this->noFallback ? optional(null) : Cache::remember('styleguide_notification_conversation_member', 5, function () use ($conversation) {
                return optional(
                    ExchangeSpaceMember::inRandomOrder()->where('exchange_space_id', optional($conversation->space)->id)
                        ->take(1)->first()
                );
            });
        }

        return $this->models = collect([
            'user' => $user,
            'search' => $search,
            'listing' => $listing,
            'space' => $space,
            'conversation' => $conversation,
            'requested_member' => optional($requested_member),
            'removed_member' => optional($removed_member),
            'conversation_member' => optional($conversation_member),
        ]);
    }
}
