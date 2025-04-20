<?php

namespace App\Observers;

use App\ExchangeSpaceMember;

class ExchangeSpaceMemberObserver
{
    /**
     * Listen to the Member deleted event.
     *
     * @param  ExchangeSpaceMember $member
     * @return void
     */
    public function deleted(ExchangeSpaceMember $member)
    {
        // Set member to inactive.
        $member->deactivate();
    }
}
