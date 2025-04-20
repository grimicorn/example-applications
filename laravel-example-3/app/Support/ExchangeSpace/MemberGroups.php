<?php

namespace App\Support\ExchangeSpace;

use Laravel\Spark\Spark;
use Illuminate\Support\Facades\Auth;
use App\Support\ExchangeSpace\MemberRole;

class MemberGroups
{
    protected $members;
    protected $space;

    public function __construct($members, $space)
    {
        $this->space = $space;
        $this->members = $this->removeUnapproved($members);
    }

    public function get()
    {
        $memberGroups = [
            'sellers' => $this->members->where('role', MemberRole::SELLER)->values()->toArray(),
            'seller_advisors' => $this->members->where('role', MemberRole::SELLER_ADVISOR)->values()->toArray(),
            'buyers' => $this->members->where('role', MemberRole::BUYER)->values()->toArray(),
            'buyer_advisors' => $this->members->where('role', MemberRole::BUYER_ADVISOR)->values()->toArray(),
        ];

        return $memberGroups;
    }

    /**
     * Removes unapproved users if the current user can not view them.
     *
     * @param Illuminate\Support\Collection $members
     *
     * @return Illuminate\Support\Collection
     */
    protected function removeUnapproved($members)
    {
        return $members;
        // Allow all developers to access all members.
        $isDeveloper = Spark::developer(Auth::user()->email);
        if ($isDeveloper) {
            return $members;
        }

        // Allow exchange space owner to access all members
        if ($this->space->user->id === Auth::id()) {
            return $members;
        }

        return $members->where('approved', true);
    }
}
