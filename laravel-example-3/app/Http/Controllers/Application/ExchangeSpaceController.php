<?php

namespace App\Http\Controllers\Application;

use App\ExchangeSpace;
use Laravel\Spark\Spark;
use Illuminate\Http\Request;
use App\Support\HasResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Support\ExchangeSpace\MemberGroups;
use App\Support\ExchangeSpace\DealStageGraph;
use App\Support\Notification\HasNotifications;
use App\Support\TableFilters\ExchangeSpaceTableFilter;

class ExchangeSpaceController extends Controller
{
    use HasResponse;
    use HasNotifications;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $spaces = (new ExchangeSpaceTableFilter($request))->paginated();

        if ($request->expectsJson()) {
            return $spaces;
        }

        return view('app.sections.exchange-space.index', [
            'pageTitle' => 'Exchange Spaces',
            'section' => 'exchange-spaces',
            'paginatedSpaces' => $spaces,
            'listingOptions' => $spaces->map(function ($space) {
                return [
                    'label' => $space->listing->title,
                    'value' => $space->listing->id,
                ];
            })->unique('value')->sortBy(function ($option) {
                return strtolower($option['label']);
            })->values(),
            'columns' => [
                [
                    'label' => 'Dashboard',
                    'sortDisabled' => true,
                    'class' => 'es-dashboard'
                ],

                [
                    'label' => 'Exchange Space',
                    'sortKey' => 'title',
                ],

                [
                    'label' => 'Stage',
                    'sortKey' => 'stage',
                ],

                [
                    'label' => 'Last Activity',
                    'isSorted' => true,
                    'sortOrder' => 'desc',
                    'sortKey' => 'updated_at',
                    'class' => 'es-last-activity'
                ],

                [
                    'label' => 'Notifications',
                    'sortKey' => 'notifications',
                ],
            ],
            'tourUrl' => '/tours/exchange-space-index',
            'tourEnabled' => false,
            'tourActivateLink' => route('exchange-spaces.index', ['tour' => 1]),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $space = ExchangeSpace::with('members')->findOrFail($id);
        if ($space->current_member->is_seller) {
            $members = $space->membersIncludingUnapproved;
        } else {
            $members = $space->members;
        }
        $members = $members->load('user', 'user.professionalInformation', 'space');

        if (request()->has('notification_all')) {
            $notifications = $space->getCurrentUserNotifications();
        } else {
            $notifications = $space->getCurrentUserNotifications()->take(10);
        }

        $loadUserId = intval(request()->get('member_user_id', 0));
        $denyRoute = '';

        if ($loadUserId > 0) {
            $loadMember = optional($members->where('user_id', $loadUserId))
            ->first();

            if ($loadMember) {
                $denyRoute = route('exchange-spaces.member.destroy', [
                    'id' => $space->id,
                    'm_id' => $loadMember->id,
                ]);
            }
        }

        return  view('app.sections.exchange-space.show', [
            'space' => $space,
            'stages' => (new DealStageGraph($space))->get(),
            'memberGroups' => (new MemberGroups($members, $space))->get(),
            'pageTitle' => 'Exchange Space',
            'pageSubtitle' => $space->title,
            'pageSubtitleEditLabel' => 'Exchange Space Name:',
            'section' => 'exchange-spaces',
            'notifications' => $notifications,
            'pageSubtitleEditRoute' => $space->title_edit_url,
            'pageSubSubtitle' => "Business: {$space->listing->title}",
            'pageSubSubtitleLink' => route('businesses.show', ['id' => $space->listing->id]),
            'loadSearch' => request()->get('member_email', ''),
            'loadUserId' => $loadUserId,
            'denyRoute' => $denyRoute,
            'tourUrl' => '/tours/exchange-space-show',
            'tourEnabled' => false,
            'tourActivateLink' => route('exchange-spaces.show', ['id' => $id, 'tour' => 1]),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $space = ExchangeSpace::findOrFail($id);

        // Set the close message
        $space->close_message = request()->get('close_message');
        $space->save();

        // Soft Delete the message
        $space->delete();

        return $this->successResponse(
            'Your Exchange Space has been successfully deleted',
            $request,
            route('exchange-spaces.index')
        );
    }

    /**
     * Gets the allowed request fields.
     *
     * @param  Request $request
     *
     * @return array
     */
    protected function getFields(Request $request)
    {
        $fields = (new ExchangeSpace)->getFillable();
        $fields = $request->all($fields);

        return collect($fields)->filter(function ($field) {
            return !is_null($field) and $field !== '';
        })->toArray();
    }
}
