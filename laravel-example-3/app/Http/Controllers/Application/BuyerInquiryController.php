<?php

namespace App\Http\Controllers\Application;

use App\Message;
use App\Listing;
use App\Conversation;
use App\ExchangeSpace;
use App\Support\HasResponse;
use Illuminate\Http\Request;
use App\ExchangeSpaceMember;
use App\Http\Controllers\Controller;
use App\Support\ExchangeSpaceDealType;
use App\Support\ExchangeSpaceStatusType;
use App\Support\ExchangeSpace\MemberRole;
use App\Support\ConversationCategoryType;
use App\Support\Notification\NotificationType;
use App\Support\Notification\HasNotifications;
use App\Support\Notification\ExchangeSpaceNotification;
use App\Support\TableFilters\BuyerInquiryConversationsFilter;

class BuyerInquiryController extends Controller
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
        $conversations = (new BuyerInquiryConversationsFilter($request))->allPaginated();

        // If we want JSON it is because we are sorting the inquiries.
        if ($request->expectsJson()) {
            return $conversations;
        }

        return view('app.sections.buyer-inquiry.index', [
            'conversations' => $conversations,
            'pageTitle' => 'Business Inquiries',
            'section' => 'buyer-inquires',
            'sortOptions' => [
                [
                    'label' => 'Newest',
                    'value' => 'newest',
                ],
                [
                    'label' => 'Oldest',
                    'value' => 'oldest',
                ],
                [
                    'label' => 'Business Title (A->Z)',
                    'value' => 'listing_title_az',
                ],
                [
                    'label' => 'Business Title (Z->A)',
                    'value' => 'listing_title_za',
                ],
                [
                    'label' => 'Inquirer (A->Z)',
                    'value' => 'inquirer_az',
                ],
                [
                    'label' => 'Inquirer (Z->A)',
                    'value' => 'inquirer_za',
                ],
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        session()->flash('inquiry_form_inputs', $request->all());

        $request->validate([
            'body' => 'required',
        ]);

        // Get the listing
        $listing = Listing::findOrFail($request->get('listing_id'));

        // Do not allow users to create duplicate active inquires/spaces
        if ($listing->userHasActiveSpace(auth()->user())) {
            return $this->abortResponse('You already have an open Inquiry for this listing.');
        }

        // Do not allow users to create inquiries for their own listing.
        if ($listing->user_id === auth()->id()) {
            return $this->abortResponse('You can not create an inquiry for your own listing.');
        }

        // Create a new exchange space.
        $space = new ExchangeSpace;
        $space->user_id = $listing->user_id;
        $space->status = ExchangeSpaceStatusType::INQUIRY;
        $space->deal = ExchangeSpaceDealType::PRE_NDA;
        $space->listing_id = $listing->id;
        $space->save();


        // Add the seller and buyer to the exchange space.
        $space->members()->saveMany([
            (new ExchangeSpaceMember)->forceFill([
                'role' => MemberRole::SELLER,
                'user_id' => $space->user_id,
                'approved' => true,
            ]),
            (new ExchangeSpaceMember)->forceFill([
                'role' => MemberRole::BUYER,
                'user_id' => auth()->id(),
                'approved' => true,
            ]),
        ]);

        $space = $space->fresh();
        $space->title = $space->buyer_seller_formatted; // seller/buyer last names
        $space->save();

        // Add the conversation
        $space->conversations()->save($conversation = new Conversation([
            'title' => 'Business Inquiry',
            'category' => ConversationCategoryType::BUYER_INQUIRY,
            'is_inquiry' => true,
        ]));
        $conversation = $conversation->fresh();

        $message = new Message;
        $message->user_id = auth()->id();
        $message->conversation_id = $conversation->id;
        $message->body = $request->get('body');
        $message->save();

        // Notify the user
        $notification = new ExchangeSpaceNotification(
            $space->fresh(),
            NotificationType::NEW_BUYER_INQUIRY
        );
        $notification->setRecipient($space->user);
        $this->dispatchNotification($notification);

        return $this->successResponse(
            'Your inquiry has been successfully submitted!',
            $request,
            $route = null,
            $data = ['space' => $space->fresh()]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $inquiry = ExchangeSpace::findOrFail($id);
        $conversation = $inquiry->conversations()->ofInquiry()->first();

        // Clear out the notifications
        $conversation->readBuyerInquiryNotifications();
        $conversation = $conversation->fresh();


        if ($inquiry->accepted()) {
            return redirect($conversation->space_url);
        }

        $messages = $conversation->messages()->ascending('created_at')->withDeletedByAdmin()->with('user')->get();
        return view('app.sections.buyer-inquiry.show', [
            'inquiry' => $inquiry,
            'space' => $inquiry,
            'conversation' => $conversation,
            'messages' => $messages,
            'pageTitle' => 'Business Inquiries',
            'section' => 'buyer-inquires',
            'pageSubtitle' => "Business: {$inquiry->listing->title}",
            'pageSubtitleLink' => route('businesses.show', ['id' => $inquiry->listing->id]),
            'isSpace' => false,
            'backLink' => route('business-inquiry.index'),
            'backLabel' => 'Back to Business Inquiries',
            'storeLink' => route('business-inquiry.conversation.store', [
                'id' => $inquiry->id,
                'c_id' => $conversation->id,
            ]),
        ]);
    }


    /**
     * Gets the allowed request fields.
     *
     * @param  Request $request
     *
     * @return array
     */
    protected function getMessageFields(Request $request)
    {
        $fields = (new Message)->getFillable();
        $fields = $request->all($fields);

        return collect($fields)->filter(function ($field) {
            return !is_null($field) and $field !== '';
        })->toArray();
    }
}
