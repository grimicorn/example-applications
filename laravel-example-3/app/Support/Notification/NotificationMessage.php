<?php
namespace App\Support\Notification;

use App\Notification;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use App\Support\Notification\NotificationType;

class NotificationMessage
{
    protected $slug;
    protected $notification;

    public function __construct(Model $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Gets the message body.
     *
     * @return string
     */
    public function getBody()
    {
        $method = collect([
            NotificationType::SAVED_SEARCH_MATCH => 'getSavedSearchMatchMessage',
            NotificationType::DEAL_UPDATED => 'getDealUpdatedMessage',
            NotificationType::STATUS_CHANGED => 'getStatusChangedMessage',
            NotificationType::DELETED_EXCHANGE_SPACE => 'getDeletedExchangeSpaceMessage',
            NotificationType::NEW_EXCHANGE_SPACE => 'getNewExchangeSpaceMessage',
            NotificationType::NEW_MEMBER => 'getNewMemberMessage',
            NotificationType::NEW_MEMBER_REQUESTED => 'getNewMemberRequestedMessage',
            NotificationType::NEW_DILIGENCE_CENTER_CONVERSATION => 'getNewDiligenceCenterConversationMessage',
            NotificationType::HISTORICAL_FINANCIAL_UNAVAILABLE => 'getHistoricalFinancialUnavailableMessage',
            NotificationType::HISTORICAL_FINANCIAL_AVAILABLE => 'getHistoricalFinancialAvailableMessage',
            NotificationType::DEAL_STAGE_NDA => 'getDealStageNdaMessage',
            NotificationType::MESSAGE => 'getMessageMessage',
            NotificationType::NEW_BUYER_INQUIRY => 'getNewBuyerInquiryMessage',
            NotificationType::ADDED_ADVISOR_SELLER => 'getAddedAdvisorMessage',
            NotificationType::ADDED_ADVISOR_BUYER => 'getAddedAdvisorMessage',
            NotificationType::REJECTED_INQUIRY => 'getRejectedInquiryMessage',
            NotificationType::REMOVED_ADVISOR_BUYER => 'getRemovedAdvisorMessage',
            NotificationType::REMOVED_ADVISOR_SELLER => 'getRemovedAdvisorMessage',
            NotificationType::REMOVED_BUYER => 'getRemovedBuyerMessage',
            NotificationType::SELLER_REMOVED_ADVISOR => 'getSellerRemovedAdvisorMessage',
            NotificationType::EXCHANGE_SPACE_MEMBER_WELCOME => 'getExchangeSpaceMemberWelcomeMessage',
        ])->get($this->notification->type, '');

        if ($method and method_exists($this, $method)) {
            return $this->$method();
        }

        return '';
    }

    /**
     * Saved search match message.
     *
     * @return string
     */
    protected function getSavedSearchMatchMessage()
    {
        return "New Watch List match for \"{$this->notification->rule_name}\"";
    }

    /**
     * Deal updated message.
     *
     * @return string
     */
    protected function getDealUpdatedMessage()
    {
        $member = optional($this->notification->member);
        return "The deal stage of Exchange Space \"{$member->custom_title}\" has been advanced to \"{$this->notification->deal_label}\"";
    }

    /**
     * Status changed message.
     *
     * @return string
     */
    protected function getStatusChangedMessage()
    {
        return "<strong>Status Change</strong> for {$this->notification->exchange_space_title} with {$this->notification->business_name}";
    }

    /**
     * Deleted exchange space message.
     *
     * @return string
     */
    protected function getDeletedExchangeSpaceMessage()
    {
        $space = optional($this->notification->exchangeSpace);

        if ($space->useListingClosed()) {
            $listing = optional($this->notification->listing);

            return "The business \"{$listing->title}\" was deleted by the seller";
        }

        $member = optional($this->notification->member);

        return "The Exchange Space \"{$member->custom_title}\" was deleted by the seller";
    }

    /**
     * New exchange space message.
     *
     * @return string
     */
    protected function getNewExchangeSpaceMessage()
    {
        $listing = optional($this->notification->listing);
        return "Your inquiry into \"{$listing->title}\" has been accepted and an Exchange Space has been created";
    }

    /**
     * New member message.
     *
     * @return string
     */
    protected function getNewMemberMessage()
    {
        return "New member <strong>{$this->notification->requested_member_name}</strong> added to <strong>{$this->notification->exchange_space_title}</strong>";
    }

    /**
     * New member requested message.
     *
     * @return string
     */
    protected function getNewMemberRequestedMessage()
    {
        $member = optional($this->notification->member);
        $requestedMember = optional($this->notification->requested_member);
        $requestedByMember = optional($requestedMember->requested_by);
        $requestedByUser = optional($requestedByMember->user);

        return "{$requestedByUser->short_name} has requested someone be added to Exchange Space \"{$member->custom_title}\"";
    }

    /**
     * New diligence center conversation message.
     *
     * @return string
     */
    protected function getNewDiligenceCenterConversationMessage()
    {
        $member = optional($this->notification->member);

        return "New Diligence Center conversation in Exchange Space \"{$member->custom_title}\"";
    }

    /**
     * Historical financial unavailable message.
     *
     * @return string
     */
    protected function getHistoricalFinancialUnavailableMessage()
    {
        $member = optional($this->notification->member);
        return "Historical Financials now unavailable for Exchange Space \"{$member->custom_title}\"";
    }

    /**
     * Historical financial available message.
     *
     * @return string
     */
    protected function getHistoricalFinancialAvailableMessage()
    {
        $member = optional($this->notification->member);
        return "Historical Financials now available for Exchange Space \"{$member->custom_title}\"";
    }

    /**
     * Deal stage nda message.
     *
     * @return string
     */
    protected function getDealStageNdaMessage()
    {
        $member = optional($this->notification->member);

        return "Exchange Space \"{$member->custom_title}\" has been advanced to \"Due Diligence\"";
    }

    /**
     * Message message.
     *
     * @return string
     */
    protected function getMessageMessage()
    {
        if (optional($this->notification->exchangeSpace)->accepted()) {
            $member = optional($this->notification->member);
            return "New Diligence Center message in Exchange Space \"{$member->custom_title}\"";
        }

        return "You have a new message in the Business Inquiry for \"{$this->notification->listing->title}\"";
    }

    /**
     * New business inquiry message.
     *
     * @return string
     */
    protected function getNewBuyerInquiryMessage()
    {
        $listing = optional($this->notification->listing);
        $space = optional($this->notification->exchangeSpace);
        $buyer = optional($space->buyer_user);
        return "New Business Inquiry for \"{$listing->title}\" from {$buyer->short_name}";
    }

    /**
     * Added advisor buyer message.
     *
     * @return string
     */
    protected function getAddedAdvisorMessage()
    {
        $member = optional($this->notification->member);
        $requestedMember = optional($this->notification->requested_member);
        $requestedUser = optional($requestedMember->user);

        return "{$requestedUser->short_name} has been added to Exchange Space \"{$member->custom_title}\" as a {$requestedMember->role_label}";
    }

    /**
     * Added rejected inquiry message.
     *
     * @return string
     */
    protected function getRejectedInquiryMessage()
    {
        return "Your inquiry into \"{$this->notification->listing->title}\" has been rejected";
    }

    /**
     * Removed advisor buyer message.
     *
     * @return void
     */
    protected function getRemovedAdvisorMessage()
    {
        $member = optional($this->notification->member);
        $removed_member = optional($this->notification->removed_member);
        $removed_user = optional($removed_member->user);
        return "{$removed_user->short_name} has left Exchange Space \"{$member->custom_title}\"";
    }


    /**
     * Removed buyer message.
     *
     * @return void
     */
    protected function getRemovedBuyerMessage()
    {
        $member = optional($this->notification->member);

        return "Exchange Space \"{$member->custom_title}\" has been closed by the buyer";
    }

    protected function getSellerRemovedAdvisorMessage()
    {
        $member = optional($this->notification->member);
        $removed_member = optional($this->notification->removed_member);
        if ($member->trashed()) {
            return "You have been removed from Exchange Space \"{$member->custom_title}\"";
        }

        $removed_user = optional($removed_member->user);
        return "{$removed_user->short_name} has been removed from Exchange Space \"{$member->custom_title}\"";
    }

    protected function getExchangeSpaceMemberWelcomeMessage()
    {
        $listing = optional($this->notification->listing);
        $member = optional($this->notification->member);
        return "You have been added to an Exchange Space for \"{$listing->title}\"";
    }

    /**
     * Gets the message icon.
     *
     * @return string
     */
    public function getIcon()
    {
        return collect([
            NotificationType::SAVED_SEARCH_MATCH => 'fa-binoculars',
            NotificationType::DEAL_UPDATED => 'fa-pencil-square-o',
            NotificationType::STATUS_CHANGED => 'fa-pencil-square-o',
            NotificationType::DELETED_EXCHANGE_SPACE => 'fa-pencil-square-o',
            NotificationType::NEW_EXCHANGE_SPACE => 'fa-pencil-square-o',
            NotificationType::NEW_MEMBER => 'fa-plus',
            NotificationType::NEW_MEMBER_REQUESTED => 'fa-user-plus',
            NotificationType::NEW_DILIGENCE_CENTER_CONVERSATION => 'fa-plus',
            NotificationType::HISTORICAL_FINANCIAL_UNAVAILABLE => 'fa-times',
            NotificationType::HISTORICAL_FINANCIAL_AVAILABLE => 'fa-check',
            NotificationType::DEAL_STAGE_NDA => 'fa-pencil-square-o',
            NotificationType::MESSAGE => 'fa-comment-o',
            NotificationType::NEW_BUYER_INQUIRY => 'fa-envelope',
            NotificationType::ADDED_ADVISOR_SELLER => 'fa-user-plus',
            NotificationType::ADDED_ADVISOR_BUYER => 'fa-user-plus',
            NotificationType::REJECTED_INQUIRY => 'fa-pencil-square-o',
            NotificationType::REMOVED_ADVISOR_BUYER => 'fa-pencil-square-o',
            NotificationType::REMOVED_ADVISOR_SELLER => 'fa-pencil-square-o',
            NotificationType::REMOVED_BUYER => 'fa-pencil-square-o',
            NotificationType::SELLER_REMOVED_ADVISOR => 'fa-times',
            NotificationType::EXCHANGE_SPACE_MEMBER_WELCOME => 'fa-plus',
        ])->get($this->notification->type, '');
    }

    /**
     * Checks if the notifications location is an exchange space.
     *
     * @return boolean
     */
    protected function isExchangeSpacePage()
    {
        $routeName = Route::getFacadeRoot()->current()->getName();

        return ($routeName === 'exchange-spaces.show');
    }
}
