<?php

namespace App\Observers;

use App\User;
use App\ExchangeSpace;
use App\Support\ExchangeSpaceStatusType;
use App\Support\Notification\HasNotifications;
use App\Support\Notification\NotificationType;
use App\Support\Notification\ExchangeSpaceNotification;
use App\Support\Notification\HistoricalFinancialNotification;

class ExchangeSpaceObserver
{
    use HasNotifications;

    /**
     * Listen to the Exchange Space deleting event.
     *
     * @param  ExchangeSpace  $space
     * @return void
     */
    public function deleting(ExchangeSpace $space)
    {
        if ($space->is_inquiry) {
            $space->rejectInquiry();
        }
    }

    /**
     * Listen to the Exchange Space deleted event.
     *
     * @param  ExchangeSpace  $space
     * @return void
     */
    public function deleted(ExchangeSpace $space)
    {
        // Delete all current notifications
        $space->clearNotifications();

        // Notify members
        $this->dispatchExchangeSpaceNotificationsOnce(
            $space,
            $space->isRejected() ? NotificationType::REJECTED_INQUIRY : NotificationType::DELETED_EXCHANGE_SPACE
        );
    }

    /**
     * Listen to the Exchange Space updating event.
     *
     * @param  ExchangeSpace  $space
     * @return void
     */
    public function updating(ExchangeSpace $space)
    {
        if ($space->transitioningFromInquiry() and !$space->isRejected()) {
            $this->dispatchExchangeSpaceNotifications(
                $space,
                NotificationType::NEW_EXCHANGE_SPACE
            );
        }

        if ($space->transitioningFinancialsPublicStatus()) {
            $space->members
            ->whereNotIn('user_id', [$space->seller_user->id])
            ->map(function ($member) use ($space) {
                $notification = new HistoricalFinancialNotification(
                    $space,
                    $space->financialsPublic() ?
                    NotificationType::HISTORICAL_FINANCIAL_AVAILABLE :
                    NotificationType::HISTORICAL_FINANCIAL_UNAVAILABLE
                );
                $notification->setRecipient($member->user);
                $this->dispatchNotification($notification);
                return $member;
            });
        }
    }

    /**
     * Listen to the Exchange Space updated event.
     *
     * @param  ExchangeSpace  $space
     * @return void
     */
    public function updated(ExchangeSpace $space)
    {
        if ($space->transitioningToSignedNDA()) {
            $this->dispatchExchangeSpaceNotifications(
                $space,
                NotificationType::DEAL_STAGE_NDA
            );
        } elseif ($space->transitioningDeal()) {
            $this->dispatchExchangeSpaceNotifications(
                $space,
                NotificationType::DEAL_UPDATED
            );
        }
    }
}
