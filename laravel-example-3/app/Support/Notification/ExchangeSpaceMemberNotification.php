<?php

namespace App\Support\Notification;

use App\ExchangeSpaceMember;
use App\Support\Notification\Notification;

class ExchangeSpaceMemberNotification extends Notification
{
    use HasExchangeSpaceNotificationHelpers;

    protected $requestedMember;
    protected $space;

    public function __construct(ExchangeSpaceMember $requestedMember, $type, $data = [])
    {
        $this->requestedMember = $requestedMember;
        $this->space = $requestedMember->space;
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
}
