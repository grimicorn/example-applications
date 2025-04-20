<?php

namespace App\Support\Notification;

use App\User;
use App\Listing;
use App\SavedSearch;
use App\Conversation;
use App\ExchangeSpace;
use App\ExchangeSpaceMember;
use App\Mail\NewNotification;
use App\Support\ExchangeSpaceDealType;
use App\Support\ExchangeSpaceStatusType;
use App\Support\Notification\NotificationType;
use App\Support\Notification\LoginNotification;
use App\Support\Notification\NewUserNotification;
use App\Support\Notification\NotificationTypeGroup;
use App\Support\Watchlist\WatchlistUserSearchResult;
use App\Support\Notification\SavedSearchNotification;
use App\Support\Notification\ConversationNotification;
use App\Support\Notification\ExchangeSpaceNotification;
use App\Support\Notification\ResetPasswordNotification;
use App\Support\Notification\SavedSearchDigestNotification;
use App\Support\Notification\DueDiligenceDigestNotification;
use App\Support\Notification\ExchangeSpaceMemberNotification;
use App\Support\Notification\HistoricalFinancialNotification;
use App\Support\Notification\ProfessionalContactedNotification;

class MailableExample
{
    protected $slug;
    protected $type;
    protected $id;

    public function __construct($slug, $id)
    {
        $this->slug = $slug;
        $this->id = $id;
        $this->type = $this->getType($slug);
    }

    protected function setupSpaceForDeletedViews($space)
    {
        switch ($this->slug) {
            case 'exchange-space-deleted-space-complete-deleted':
                $space->status = ExchangeSpaceStatusType::COMPLETED;
                $space->deal = ExchangeSpaceDealType::COMPLETE;
                break;

            case 'exchange-space-deleted-space-deleted':
                $space->deleted_at = $space->freshTimestamp();
                break;
        }

        return $space;
    }

    protected function getType($slug)
    {
        $isDeleted = collect([
            'exchange-space-deleted-space-complete-deleted',
            'exchange-space-deleted-listing-deleted',
            'exchange-space-deleted-space-deleted',
        ])->contains($slug);
        if ($isDeleted) {
            return NotificationType::DELETED_EXCHANGE_SPACE;
        }

        return NotificationType::getSlugs()->flip()->get($slug);
    }

    protected function getMailableExampleMethods()
    {
        return collect([
            NotificationType::SAVED_SEARCH_MATCH_DIGEST => 'savedSearchMatchMailable',
            NotificationType::MESSAGE => 'messageMailable',
            NotificationType::ADDED_ADVISOR_BUYER => 'exchangeSpaceMemberMailable',
            NotificationType::ADDED_ADVISOR_SELLER => 'exchangeSpaceMemberMailable',
            NotificationType::NEW_BUYER_INQUIRY => 'exchangeSpaceMailable',
            NotificationType::NEW_MEMBER => 'exchangeSpaceMemberMailable',
            NotificationType::NEW_EXCHANGE_SPACE => 'exchangeSpaceMailable',
            NotificationType::DELETED_EXCHANGE_SPACE => 'exchangeSpaceMailable',
            NotificationType::STATUS_CHANGED => 'exchangeSpaceMailable',
            NotificationType::DEAL_UPDATED => 'exchangeSpaceMailable',
            NotificationType::LOGIN => 'loginMailable',
            NotificationType::NEW_MEMBER_REQUESTED => 'exchangeSpaceMemberMailable',
            NotificationType::HISTORICAL_FINANCIAL_AVAILABLE => 'historicalFinancialAvailableMailable',
            NotificationType::HISTORICAL_FINANCIAL_UNAVAILABLE => 'historicalFinancialUnavailableMailable',
            NotificationType::NEW_USER => 'newUserMailable',
            NotificationType::NEW_DILIGENCE_CENTER_CONVERSATION => 'newDiligenceCenterConversationMailable',
            NotificationType::DEAL_STAGE_NDA => 'exchangeSpaceMailable',
            NotificationType::RESET_PASSWORD => 'resetPasswordMailable',
            NotificationType::REJECTED_INQUIRY => 'exchangeSpaceMailable',
            NotificationType::REMOVED_ADVISOR_BUYER => 'exchangeSpaceMemberMailable',
            NotificationType::REMOVED_ADVISOR_SELLER => 'exchangeSpaceMemberMailable',
            NotificationType::CLOSED_ACCOUNT => 'closedAccountMailable',
            NotificationType::REMOVED_BUYER => 'exchangeSpaceMemberMailable',
            NotificationType::SELLER_REMOVED_ADVISOR => 'exchangeSpaceMemberMailable',
            NotificationType::DUE_DILIGENCE_DIGEST => 'dueDiligenceDigestMailable',
            NotificationType::EXCHANGE_SPACE_MEMBER_WELCOME => 'exchangeSpaceMemberMailable',
            NotificationType::PROFESSIONAL_CONTACTED => 'professionalContactedMailable',
        ]);
    }

    public function get()
    {
        $method = $this->getMailableExampleMethods()->get($this->type);
        if (method_exists($this, $method)) {
            return $this->$method();
        }

        return "Notification type {$this->slug} does not exist";
    }

    protected function outputDetails($notification)
    {
        echo view('app.sections.notifications.email.partials.example-details', [
            'subject' => $notification->emailSubject(),
            'notificationLink' => $this->notificationLink(),
        ])->render();
    }

    protected function notificationLink()
    {
        $data = request()->all();
        $data['type'] = $this->type;

        if ($this->type === NotificationType::SAVED_SEARCH_MATCH_DIGEST) {
            return;
        }

        switch (NotificationType::getGroupKey($this->type)) {
            case NotificationTypeGroup::EXCHANGE_SPACE:
                $data['space_id'] = $this->id;
                break;

            case NotificationTypeGroup::SAVED_SEARCH:
                $data['saved_search_id'] = $this->id;
                break;

            case NotificationTypeGroup::CONVERSATION:
                $data['conversation_id'] = $this->id;
                break;
        }

        return route('styleguide.show.notification', $data);
    }

    protected function professionalContactedMailable()
    {
        $faker = \Faker\Factory::create();
        $notification = new ProfessionalContactedNotification(
            User::findOrFail($this->id),
            $fields = [
                'name' => "{$faker->firstName} {$faker->lastName}",
                'phone' => $faker->phoneNumber,
                'email' => $faker->email,
                'message' => $faker->paragraphs(3, true),
            ]
        );

        $this->outputDetails($notification);

        return new NewNotification($notification);
    }

    protected function historicalFinancialAvailableMailable()
    {
        $space = ExchangeSpace::withTrashed()->findOrFail($this->id);
        $notification = new HistoricalFinancialNotification(
            $space,
            NotificationType::HISTORICAL_FINANCIAL_AVAILABLE
        );
        $notification = $this->updateNotificationRecipient($notification);
        $this->outputDetails($notification);

        return new NewNotification($notification);
    }

    protected function historicalFinancialUnavailableMailable()
    {
        $space = ExchangeSpace::withTrashed()->findOrFail($this->id);
        $notification = new HistoricalFinancialNotification(
            $space,
            NotificationType::HISTORICAL_FINANCIAL_UNAVAILABLE
        );
        $notification = $this->updateNotificationRecipient($notification);
        $this->outputDetails($notification);

        return new NewNotification($notification);
    }

    protected function savedSearchMatchMailable()
    {
        $user = User::findOrFail($this->id);
        $notification = new SavedSearchDigestNotification($user);
        $notification = $this->updateNotificationRecipient($notification);
        $this->outputDetails($notification);

        return new NewNotification($notification);
    }

    protected function newDiligenceCenterConversationMailable()
    {
        $conversation = Conversation::withTrashed()->findOrFail($this->id);
        $notification = new ConversationNotification(
            $conversation,
            NotificationType::NEW_DILIGENCE_CENTER_CONVERSATION
        );
        $notification = $this->updateNotificationRecipient($notification);
        $this->outputDetails($notification);

        return new NewNotification($notification);
    }

    protected function messageMailable()
    {
        $conversation = Conversation::withTrashed()->findOrFail($this->id);
        $notification = new ConversationNotification(
            $conversation,
            NotificationType::MESSAGE
        );
        $notification = $this->updateNotificationRecipient($notification);
        $this->outputDetails($notification);

        return new NewNotification($notification);
    }

    protected function newUserMailable()
    {
        $user = User::withTrashed()->findOrFail($this->id);
        $notification = new NewUserNotification($user);
        $this->outputDetails($notification);

        return new NewNotification($notification);
    }

    protected function closedAccountMailable()
    {
        $notification = new ClosedAccountNotification(User::withTrashed()->findOrFail($this->id));
        $this->outputDetails($notification);

        return new NewNotification($notification);
    }

    protected function loginMailable()
    {
        $notification = new LoginNotification;
        $this->outputDetails($notification);

        return new NewNotification($notification);
    }

    public function resetPasswordMailable()
    {
        $user = User::withTrashed()->findOrFail($this->id);
        $token = sha1(time());
        $notification = new ResetPasswordNotification($user, $token);
        $this->outputDetails($notification);

        return new NewNotification($notification);
    }

    protected function exchangeSpaceMailable()
    {
        $space = ExchangeSpace::withTrashed()->findOrFail($this->id);
        $space = $this->setupSpaceForDeletedViews($space);

        // Override the space's deal stage if supplied
        if (request()->has('deal')) {
            $space->deal = request()->get('deal');
        }

        $notification = new ExchangeSpaceNotification($space, $this->type);
        $notification = $this->updateNotificationRecipient($notification);
        $this->outputDetails($notification);

        return new NewNotification($notification);
    }

    protected function exchangeSpaceMemberMailable()
    {
        $space = ExchangeSpace::withTrashed()->findOrFail($this->id);
        $member = $this->getRequestedMember($space);
        $data = array_filter([
            'removed_member_name' => optional(optional($this->getRemovedMember($space))->user)->name,
            'removed_member' => $this->getRemovedMember($space),
        ]);

        $notification = new ExchangeSpaceMemberNotification($member, $this->type, $data);
        $notification = $this->updateNotificationRecipient($notification);
        $this->outputDetails($notification);

        return new NewNotification($notification);
    }

    protected function dueDiligenceDigestMailable()
    {
        $user = User::withTrashed()->findOrFail($this->id);
        $notification = new DueDiligenceDigestNotification($user);
        $notification = $this->updateNotificationRecipient($notification);
        $this->outputDetails($notification);

        return new NewNotification($notification);
    }

    protected function updateNotificationRecipient($notification)
    {
        if (request()->get('recipient_user_id')) {
            $notification->setRecipient(User::withTrashed()->findOrFail(request()->get('recipient_user_id')));
        }

        return  $notification;
    }

    protected function getRemovedMember($space)
    {
        return $this->getMemberByUserId($space, request()->get('removed_user_id'));
    }

    protected function getMemberByUserId($space, $user_id = null)
    {
        if ($user_id) {
            return $space->allMembers()->withTrashed()->where('user_id', $user_id)->get()->first();
        }

        return $space->allMembers()->withTrashed()->inRandomOrder()->take(1)->first();
    }

    protected function getListing()
    {
        if (request()->has('listing_id')) {
            return Listing::withTrashed()->findOrFail(request()->get('listing_id'));
        }

        return optional(Listing::inRandomOrder()->inRandomOrder()->take(1)->first());
    }

    protected function getRequestedMember($space)
    {
        // Set the member
        $member = $this->getMemberByUserId($space, request()->get('requested_user_id'));

        // Set the requester
        if (request()->get('requester_user_id')) {
            $member->requested_by_id = optional(
                $space->allMembers()
                ->where('user_id', request()->get('requester_user_id'))
                ->withTrashed()->first()
            )->id;
        }

        return $member;
    }
}
