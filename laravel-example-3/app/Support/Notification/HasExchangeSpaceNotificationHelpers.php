<?php
namespace App\Support\Notification;

use App\Support\Notification\ReportsAbuse;
use App\ExchangeSpaceNotification as ExchangeSpaceNotificationModel;
use App\Support\ExchangeSpaceDealType;

trait HasExchangeSpaceNotificationHelpers
{
    use ReportsAbuse;

    protected function addNotificationSpecificDataDefaults($data)
    {
        $data = collect($data);

        $space = optional($this->space);
        $requestedMember = isset($this->requestedMember) ? $this->requestedMember : null;
        $requestedMember = optional($requestedMember);

        $data->put('rejected_reason', $this->getRejectedReason($data));
        $data->put('rejected_explanation', $this->getRejectedExplanation($data));
        $data->put('exit_message', $data->get('exit_message', request()->get('exit_message')));
        $data->put('close_message', $this->getCloseMessage($data));
        $data->put('removed_member_name', $data->get('removed_member_name'));
        $data->put('buyer_name', optional($space->buyer_user)->name);
        $data->put('requested_member_name', optional($requestedMember->user)->name);
        $data->put('requested_member_id', optional($requestedMember)->id);


        return $data;
    }

    protected function getRejectedReason($data)
    {
        $reason = $data->get('rejected_reason', request()->get('reason'));
        if ($reason) {
            return $reason;
        }

        $space = optional($this->space);
        $rejection = optional($space->rejectionReason);
        if ($rejection->reason) {
            return $rejection->reason;
        }

        if (optional(optional($this->space)->listing)->trashed()) {
            return 'Business Removed';
        }
    }

    protected function getRejectedExplanation($data)
    {
        // First try to get the explanation off of the request, passed in data and the space close message.
        $explanation = $data->get(
            'rejected_explanation',
            request()->get(
                'explanation',
                $this->getCloseMessage($data)
            )
        );

        if ($explanation) {
            return $explanation;
        }

        $space = optional($this->space);
        $rejection = optional($space->rejectionReason);
        if ($rejection->explanation) {
            return $rejection->explanation;
        }
    }

    protected function getCloseMessage($data)
    {
        // Try to get the close message
        $message = $data->get('close_message', request()->get('close_message'));
        if ($message) {
            return $message;
        }

        // Try to get the participant message
        $message = $data->get('participant_message', request()->get('participant_message'));
        if ($message) {
            return $message;
        }

        $space = optional($this->space);
        $listing = optional($space->listing);
        $survey = optional($listing->exitSurvey);

        return $survey->participant_message;
    }

    public function sharedViewData(array $extraData = [])
    {
        $space = $this->recipientSpace();
        $member = optional($this->recipientMember());
        $requestedMember = isset($this->requestedMember) ? $this->requestedMember : null;
        $requestedMember = optional($requestedMember);
        $removedMember = optional($this->data->get('removed_member'));
        $removedUser = optional($removedMember->user);
        $requestedBy = optional($requestedMember->requested_by);
        $requestedUser = optional($requestedMember->user);
        $listing = optional($space->listing()->withTrashed()->first());
        $buyerUser = optional($space->buyer_user);
        $sellerUser = optional($space->seller_user);

        return array_merge([
            'is_seller' => $member->is_seller,
            'use_listing_closed' => $this->space->useListingClosed(),
            'original_space' => $this->space,
            'listing_title' => $listing->title,
            'business_name' => $listing->getAttributes()['business_name'] ?? '',
            'exchange_space_title' => optional($member)->custom_title,
            'recipient_member_role' => optional($member)->role_label,
            'recipient_member_removed' => optional($member)->trashed(),
            'exchange_space_url' => $this->getExchangeSpaceUrl(),
            'seller_name' => $sellerUser->name,
            'seller_first_name' => $sellerUser->first_name,
            'seller_last_name' => $sellerUser->last_name,
            'buyer_profile_url' => $buyerUser->profile_url,
            'buyer_first_name' => $buyerUser->first_name,
            'buyer_last_name' => $buyerUser->last_name,
            'profile_link' => $requestedUser->profile_url,
            'user_name' => $requestedUser->name,
            'requester_user_name' => optional($requestedBy->user)->name,
            'requester_profile_url' => optional($requestedBy->user)->profile_url,
            'requester_roll' => $requestedBy->role_label,
            'removed_member_name' => $removedUser->name,
            'removed_member_profile_url' => $removedUser->profile_url,
            'removed_member_role_label' => $removedMember->role_label,
            'user_occupation' => optional($requestedUser->professionalInformation)->occupation_label,
            'deal_label' => optional($this->space)->deal_label,
            'previous_deal_label' => $this->previousDealLabel(),
            'space' => $space,
            'status_label' => $space->status_label,
            'diligence_center_url' => route('exchange-spaces.conversations.index', ['id' => $space->id]),
            'blog_url' => config('app.blog_url'),
            'message' => $message = $this->getUserMessage(),
            'rejected_reason' => $this->data->get('rejected_reason'),
            'is_default_message' => $this->isDefaultMessage($message, $listing),
            'watchlists_url' => route('watchlists.index'),
            'listings_search_url' => route('businesses.search-landing'),
            'requested_member_role' => $requestedMember->role_label,
            'requested_member_profile_url' => $requestedUser->profile_url,
            'requested_member_short_name' => $requestedUser->short_name,
            'requested_member_name' => $requestedUser->name,
            'report_abuse_link' => $this->reportNotificationAbuseLink(
                $this->type,
                $message,
                $this->recipient()->id
            ),
        ], $extraData);
    }

    protected function previousDealLabel()
    {
        return collect(ExchangeSpaceDealType::getLabels())
        ->get(intval($this->space->deal) - 1);
    }

    protected function isDefaultMessage($explanation, $listing)
    {
        if (!$explanation) {
            return true;
        }

        return $explanation === $listing->default_participant_message;
    }

    protected function getUserMessage()
    {
        $postedMessage = $this->data->get('exit_message') ?? $this->data->get('close_message');

        switch ($this->type) {
            case NotificationType::REJECTED_INQUIRY:
                return $this->data->get('rejected_explanation') ?? '';
                break;

            case NotificationType::NEW_BUYER_INQUIRY:
                return $this->space->conversations->first()->messages->first()->body;
                break;

            case NotificationType::DELETED_EXCHANGE_SPACE:
                return $postedMessage ?? optional($this->space->listing->exitSurvey)->participant_message;
                break;

            default:
                return $postedMessage;
                break;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function emailSubject()
    {
        return NotificationType::emailSubject($this->type, [
            'space' => $this->recipientSpace(),
            'member' => $this->recipientMember(),
            'original_space' => $this->space,
        ]);
    }

    protected function recipientMember()
    {
        return $this->space->allMembers()->withTrashed()
        ->where('user_id', $this->recipient()->id)->first();
    }

    protected function recipientSpace()
    {
        $space = optional($this->recipientMember())->space;

        return $space ?: $this->space;
    }

    /**
     * Saves an exchange space.
     */
    public function saveExchangeSpace()
    {
        $space = ExchangeSpaceNotificationModel::create(array_merge([
            'user_id' => $this->recipient()->id,
            'exchange_space_id' => $this->space->id,
            'type' => $this->type,
            'exchange_space_title' => $this->space->title,
            'exchange_space_deal' => $this->space->deal,
            'exchange_space_status' => $this->space->status,
            'business_name' => $this->space->listing()->withTrashed()->first()->title,
            'buyer_name' => optional($this->space->buyer_user)->name,
            'deal_label' => $this->space->deal_label,
            'requested_member_name' => $this->data->get('requested_member_name'),
            'requested_member_id' => $this->data->get('requested_member_name'),
            'removed_member_id' => optional($this->data->get('removed_member'))->id,
            'removed_member_name' => $this->data->get('removed_member_name'),
            'rejected_reason' => $this->data->get('rejected_reason'),
            'rejected_explanation' => $this->data->get('rejected_explanation'),
            'exit_message' => $this->data->get('exit_message'),
            'close_message' => $this->data->get('close_message'),
        ], $this->data->toArray()));
    }

    protected function getExchangeSpaceUrl()
    {
        return optional($this->space)->notificationUrl($this->type, [
            'requested_member' => $this->requestedMember ?? null,
        ]);
    }
}
