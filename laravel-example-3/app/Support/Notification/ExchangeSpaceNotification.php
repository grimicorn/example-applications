<?php
namespace App\Support\Notification;

use App\ExchangeSpace;
use App\Support\Notification\Notification;
use App\Support\Notification\NotificationType;
use App\Support\Notification\HasExchangeSpaceNotificationHelpers;

class ExchangeSpaceNotification extends Notification
{
    use HasExchangeSpaceNotificationHelpers;

    protected $space;

    public function __construct(ExchangeSpace $space, $type, $data = [])
    {
        $this->space = $space;
        $data = $this->addNotificationSpecificDataDefaults($data);
        parent::__construct($type, $data);
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

    /**
     * {@inheritDoc}
     */
    public function getViewSlugFromType()
    {
        return parent::getViewSlugFromType();
    }

    /**
     * {@inheritDoc}
     */
    public function shouldEmail()
    {
        $forceEmail = collect([
            NotificationType::DELETED_EXCHANGE_SPACE,
            NotificationType::REJECTED_INQUIRY,
            NotificationType::REMOVED_BUYER,
            NotificationType::SELLER_REMOVED_ADVISOR,
        ])->contains($this->type);

        return $forceEmail ? true : parent::shouldEmail();
    }
}
