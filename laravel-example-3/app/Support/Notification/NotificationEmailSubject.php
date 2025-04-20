<?php

namespace App\Support\Notification;

use App\Support\Notification\NotificationType;

class NotificationEmailSubject
{
    protected $type;
    protected $data;

    public function __construct(int $type, array $data = [])
    {
        $this->type = $type;
        $this->data = r_collect($data);
    }

    /**
     * Get the notification email subject.
     *
     * @return string
     */
    public function get(): string
    {
        return "Firm Exchange - {$this->subjectText()}";
    }

    protected function subjectText()
    {
        $data = $this->data;
        $search = optional($data->get('search'));
        $recipient = $data->get('recipient', auth()->user());
        $space = optional($data->get('space'));
        $listing = optional($space->listing);
        $original_space = optional($data->get('original_space'));
        $member = optional($data->get('member'));
        $conversation = optional($data->get('conversation'));

        switch ($this->type) {
            case NotificationType::ADDED_ADVISOR_BUYER:
                return "A new member has been added to Exchange Space \"{$member->custom_title}\"";
                break;

            case NotificationType::ADDED_ADVISOR_SELLER:
                return "A new member has been added to Exchange Space \"{$member->custom_title}\"";
                break;

            case NotificationType::NEW_MEMBER:
                return 'Welcome to Firm Exchange!';
                break;

            case NotificationType::NEW_MEMBER_REQUESTED:
                return "A member of Exchange Space \"{$member->custom_title}\" has requested someone be added";
                break;

            case NotificationType::NEW_DILIGENCE_CENTER_CONVERSATION:
                return "New Diligence Center conversation in Exchange Space \"{$member->custom_title}\"";
                break;

            case NotificationType::MESSAGE:
                if ($space->accepted()) {
                    return "New Diligence Center message in Exchange Space \"{$member->custom_title}\"";
                }

                return 'You have received a new Business Inquiry message ';
                break;

            case NotificationType::NEW_EXCHANGE_SPACE:
                return "Your inquiry into \"{$listing->title}\" has been accepted and an Exchange Space has been created";
                break;

            case NotificationType::DELETED_EXCHANGE_SPACE:
                if ($space->useListingClosed()) {
                    return "The business \"{$listing->title}\" was deleted by the seller";
                }

                return "The Exchange Space \"{$member->custom_title}\" was deleted by the seller";
                break;

            case NotificationType::STATUS_CHANGED:
                return "Deal stage updated by seller to \"{$space->status_label}\"";
                break;

            case NotificationType::DEAL_UPDATED:
                return "The deal stage of Exchange Space \"{$member->custom_title}\" has been advanced to \"{$original_space->deal_label}\"";
                break;

            case NotificationType::NEW_BUYER_INQUIRY:
                return 'You have received a new Business Inquiry';
                break;

            case NotificationType::DEAL_STAGE_NDA:
                return "The deal stage of Exchange Space \"{$member->custom_title}\" has been advanced to \"Due Diligence\"";
                break;

            case NotificationType::REJECTED_INQUIRY:
                return "Your inquiry into \"{$listing->title}\" has been rejected";
                break;

            case NotificationType::HISTORICAL_FINANCIAL_AVAILABLE:
                return "Historical Financials now available for Exchange Space \"{$member->custom_title}\"";
                break;

            case NotificationType::NEW_USER:
                return 'Welcome to Firm Exchange!';
                break;

            case NotificationType::HISTORICAL_FINANCIAL_UNAVAILABLE:
                return "Historical Financials now unavailable for Exchange Space \"{$member->custom_title}\"";
                break;

            case NotificationType::RESET_PASSWORD:
                return 'Password reset request';
                break;

            case NotificationType::SAVED_SEARCH_MATCH_DIGEST:
                return "New Watch List matches";
                break;

            case NotificationType::LOGIN:
                return 'Sign-in detected from a new device';
                break;

            case NotificationType::REMOVED_ADVISOR_BUYER:
                return "A member has left Exchange Space \"{$member->custom_title}\"";
                break;

            case NotificationType::REMOVED_ADVISOR_SELLER:
                return "A member has left Exchange Space \"{$member->custom_title}\"";
                break;

            case NotificationType::CLOSED_ACCOUNT:
                return "Your account has been closed";
                break;

            case NotificationType::REMOVED_BUYER:
                return "Exchange Space \"{$member->custom_title}\" has been closed by the buyer";
                break;

            case NotificationType::SELLER_REMOVED_ADVISOR:
                if ($member->trashed()) {
                    return "You have been removed from Exchange Space \"{$member->custom_title}\"";
                }

                return "A member has been removed from Exchange Space \"{$member->custom_title}\"";
                break;

            case NotificationType::DUE_DILIGENCE_DIGEST:
                return "Daily Summary of Diligence Center Notifications";
                break;

            case NotificationType::EXCHANGE_SPACE_MEMBER_WELCOME:
                return "You have been added to an Exchange Space on FirmExchange.com";
                break;

            case NotificationType::PROFESSIONAL_CONTACTED:
                return "New message from a user";
                break;

            default:
                return '';
                break;
        }
    }
}
