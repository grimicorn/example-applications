<?php

namespace App\Support\Notification;

use App\ExchangeSpace;
use App\Support\Notification\Notification;
use App\Support\Notification\NotificationType;
use App\Support\Notification\HasExchangeSpaceNotificationHelpers;

class HistoricalFinancialNotification extends Notification
{
    use HasExchangeSpaceNotificationHelpers;

    protected $space;

    public function __construct(ExchangeSpace $space, $type, $data = [])
    {
        $data = $this->addNotificationSpecificDataDefaults($data);
        parent::__construct($type, $data);
        $this->space = $space;
    }

    /**
     * {@inheritDoc}
     */
    public function save()
    {
        return $this->saveExchangeSpace();
    }

    /**
     * {@inheritDoc}
     */
    public function emailBody()
    {
        $viewSlug = $this->getViewSlugFromType();
        return view("app.sections.notifications.email.{$viewSlug}", $this->sharedViewData());
    }

    protected function getUrl()
    {
        switch ($this->type) {
            case NotificationType::HISTORICAL_FINANCIAL_AVAILABLE:
                return route('exchange-spaces.historical-financials.show', ['id' => $this->space->id]);
                break;
            case NotificationType::HISTORICAL_FINANCIAL_UNAVAILABLE:
                return route('exchange-spaces.conversations.index', ['id' => $this->space->id]);
                break;
            default:
                return $this->space->show_url;
                break;
        }
    }
}
