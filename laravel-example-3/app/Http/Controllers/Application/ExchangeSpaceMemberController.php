<?php

namespace App\Http\Controllers\Application;

use App\User;
use App\ExchangeSpace;
use Illuminate\Http\Request;
use App\Support\HasResponse;
use App\ExchangeSpaceMember;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Support\ExchangeSpace\MemberRole;
use App\Support\Notification\HasNotifications;
use App\Support\Notification\NotificationType;
use App\Support\Notification\ExchangeSpaceMemberNotification;

class ExchangeSpaceMemberController extends Controller
{
    use HasResponse;
    use HasNotifications;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        $space = ExchangeSpace::findOrFail($id);

        if ($user_id = intval($request->get('user_id'))) {
            $results = array_filter([
                User::find($user_id)
            ]);
        } elseif ((bool) $request->get('members', false)) {
            $results = $this->getMembersList($space);
        } else {
            $results = $this->addMemberSearch($space);
        }

        if (!$results) {
            return ['results' => []];
        }

        if (is_array($results)) {
            $results = collect($results);
        }

        return [
            'results' => $results ? $results->filter(function ($result) {
                return is_null($result->deleted_at);
            }) : null,
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|numeric',
        ]);

        // First attempt to retrieve the user if the user does not exist then we know something is wrong.
        $user = User::findOrFail($request->get('user_id'));

        // Get the space.
        $space = ExchangeSpace::findOrFail($id);

        // Get the member if they already exist if not create them. Then set a few things for the request.
        $member = ExchangeSpaceMember::withTrashed()->where([
            'user_id' => $user->id,
            'exchange_space_id' => $space->id,
        ])->first();

        if (is_null($member)) {
            $member = new ExchangeSpaceMember();
            $member->user_id = $user->id;
            $member->exchange_space_id = $space->id;
        }

        // Activate the user or request a review
        if ($space->current_member->is_seller) {
            $member->activate();
            $status = 'Member added successfully!';
        } else {
            $member->setPending();
            $status = 'Member requested successfully!';
        }

        // Alert the seller if this is just a request.
        if (!$space->current_member->is_seller) {
            $notification = new ExchangeSpaceMemberNotification(
                $member,
                NotificationType::NEW_MEMBER_REQUESTED
            );
            $notification->setRecipient(User::findOrFail($space->seller_user->id));
            $this->dispatchNotification($notification);
        } else {
            // Alert the other members
            $this->dispatchNewExchangeSpaceMemberNotifications(
                $member,
                $member->requestedBySeller() ? NotificationType::ADDED_ADVISOR_SELLER : NotificationType::ADDED_ADVISOR_BUYER
            );
        }

        return $this->successResponse($status, $request, null, [
            'member' => $member->load('user', 'user.professionalInformation'),
            'group' => $this->getGroup($member),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $m_id)
    {
        $space = ExchangeSpace::findOrFail($id);
        $member = $space->allMembers->where('id', $m_id)->first();
        $isSeller = $space->current_member->is_seller;

        // We do not want to "remove" sellers.
        if ($member->is_seller) {
            abort(403);
        }

        // We only want to allow non-sellers to remove themselves.
        $isLeaving = $member->user->id === auth()->id();
        if (!$isSeller and !$isLeaving) {
            abort(403);
        }

        // If the deactivated member was a buyer deactivate everyone that is not a seller.
        // If not just deactivate the member.
        $member->deactivate(request()->get('exit_message'));

        return $this->successResponse(
            $isLeaving ? 'You have been successfully removed!' : 'Member removed successfully!',
            request(),
            $isLeaving ? route('exchange-spaces.index') : null,
            [
                'member' => $member,
                'group' => $this->getGroup($member),
            ]
        );
    }

    public function getGroup($member)
    {
        switch ($member->role) {
            case MemberRole::BUYER:
                $group = 'buyers';
                break;

            case MemberRole::BUYER_ADVISOR:
                $group = 'buyer_advisors';
                break;

            case MemberRole::SELLER:
                $group = 'sellers';
                break;

            case MemberRole::SELLER_ADVISOR:
                $group = 'seller_advisors';
                break;

            default:
                $group = '';
                break;
        }

        return $group;
    }

    protected function addMemberSearch(ExchangeSpace $space)
    {
        return User::search(request()->get('search', ''))->get()
        ->whereNotIn(
            'id',
            $space->members->map(function ($member) {
                return $member->user->id;
            })->concat($space->membersPending->map(function ($member) {
                return $member->user->id;
            }))
            ->unique()
            ->toArray()
        );
    }

    protected function getMembersList(ExchangeSpace $space)
    {
        return $space->membersIncludingUnapproved
        ->filter->not_seller
        ->filter->not_buyer
        ->map(function ($member) {
            $user = $member->user;
            $user->member_id = $member->id;

            return $user;
        });
    }
}
