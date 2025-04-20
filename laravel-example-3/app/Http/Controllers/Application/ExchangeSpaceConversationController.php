<?php

namespace App\Http\Controllers\Application;

use App\Message;
use App\Conversation;
use App\ExchangeSpace;
use Illuminate\Http\Request;
use App\Support\HasResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Support\ExchangeSpaceStatusType;
use App\Support\ConversationCategoryType;
use App\Support\ExchangeSpace\Attachments;
use App\Support\TableFilters\ExchangeSpaceConversationFilter;

class ExchangeSpaceConversationController extends Controller
{
    use HasResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        $space = ExchangeSpace::with('members', 'conversations', 'conversations.messages')->findOrFail($id);
        $perPage = $request->has('all') ? 1000 : 4;
        $conversations = (new ExchangeSpaceConversationFilter($request, $space))->paginated($perPage);

        if ($request->expectsJson()) {
            return $conversations;
        }

        $documents = $space->getFilesForDisplay()
        ->map(function ($document) {
            $owner_id = $document->getCustomProperty('user_id');
            $document->undeletable = $owner_id !== auth()->id();

            return $document;
        });

        return  view('app.sections.exchange-space.diligence-center.index', [
            'documents' => $documents,
            'conversations' => $conversations,
            'pageTitle' => 'Exchange Space',
            'pageSubSubtitle' => "Business: {$space->listing->title}",
            'pageSubSubtitleLink' => route('businesses.show', ['id' => $space->listing->id]),
            'pageSubtitle' => $space->title,
            'section' => 'exchange-spaces',
            'unresolvedCount' => $space->unresolvedCount(),
            'space' => $space,
            'categoryOptions' => array_map(function ($category) {
                return [
                    'label' => ConversationCategoryType::getLabel($category),
                    'value' => $category,
                ];
            }, ConversationCategoryType::getValues()),
            'statusOptions' => [
                [ 'label' => 'Resolved', 'value' => 1 ],
                [ 'label' => 'Unresolved', 'value' => 0 ],
            ],
            'tourUrl' => '/tours/diligence-center-index',
            'tourEnabled' => false,
            'tourActivateLink' => route('exchange-spaces.conversations.index', ['id' => $space->id, 'tour' => 1]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id)
    {
        $space = ExchangeSpace::findOrFail($id);
        $conversation = new Conversation;
        $conversation->exchange_space_id = $id;

        return view('app.sections.exchange-space.diligence-center.show', [
            'pageTitle' => 'Exchange Space',
            'pageSubSubtitle' => "Business: {$space->listing->title}",
            'pageSubSubtitleLink' => route('businesses.show', ['id' => $space->listing->id]),
            'pageSubtitle' => $space->title,
            'section' => 'exchange-spaces',
            'conversation' => $conversation,
            'space' => $space,
            'isCreate' => true,
            'categoryOptions' => array_map(function ($category) {
                return [
                    'label' => ConversationCategoryType::getLabel($category),
                    'value' => $category,
                ];
            }, ConversationCategoryType::getValues()),
            'backLink' => route('exchange-spaces.conversations.index', [
                'id' => $space->id,
            ]),
            'backLabel' => 'Back to All Conversations',
            'isSpace' => true,
            'storeLink' => route('exchange-spaces.conversations.store', [
                'id' => $id,
            ]),
        ]);
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
            'title' => 'required|string',
            'category' => 'required|numeric',
            'body' => 'required|string',
            'files.new.*' =>  'nullable|mimes:doc,docx,pdf,xls,xlsx,jpg,jpeg,bmp,png,pptx,ppt',
        ]);

        // Create the conversation
        $conversation = new Conversation;
        $conversation->exchange_space_id = $id;
        $conversation->fill([
            'resolved' => false,
            'title' => $request->get('title'),
            'category' =>  $request->get('category'),
        ]);
        $conversation->save();
        $conversation = $conversation->fresh();

        // Create the initial message.
        $message = new Message;
        $message->user_id = Auth::id();
        $message->conversation_id = $conversation->id;
        $message->body = $request->get('body');
        $message->save();
        $message = $message->fresh();

        // Upload the files.
        (new Attachments($message))->upload('files');

        // Dispatch the notification
        $conversation->fresh()->dispatchCreatedNotification();

        return $this->successResponse(
            'You have created a new conversation!',
            $request,
            route(
                'exchange-spaces.conversations.show',
                [
                    'id' => $id,
                    'c_id' => $conversation->id,
                ]
            )
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $c_id)
    {
        $space = ExchangeSpace::findOrFail($id);
        $conversation = Conversation::findOrFail($c_id);
        $messages = $conversation->messages()->ascending('created_at')->withDeletedByAdmin()->with('user')->get();

        if ($conversation->is_buyer_inquiry_category) {
            $view = 'app.sections.buyer-inquiry.show';
        } else {
            $view = 'app.sections.exchange-space.diligence-center.show';
        }

        $conversation->readExchangeSpaceNotifications();

        return view($view, [
            'inquiry' => $space,
            'space' => $space,
            'conversation' => $conversation,
            'messages' => $messages,
            'pageTitle' => $space->is_inquiry ? 'Business Inquiry' : 'Exchange Space',
            'section' => 'exchange-spaces',
            'pageSubtitle' => $space->title,
            'pageSubSubtitle' => "Business: {$space->listing->title}",
            'pageSubSubtitleLink' => route('businesses.show', ['id' => $space->listing->id]),
            'isSpace' => !$conversation->is_buyer_inquiry_category,
            'backLink' => route('exchange-spaces.conversations.index', [
                'id' => $space->id
            ]),
            'backLabel' => 'Back to All Conversations',
            'storeLink' => route('exchange-spaces.conversations.update', [
                'id' => $space->id,
                'c_id' => $c_id,
            ]),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $c_id)
    {
        $request->validate([
            'body' => 'required|string',
            'files.new.*' =>  'nullable|mimes:doc,docx,pdf,xls,xlsx,jpg,jpeg,bmp,png,pptx,ppt',
        ]);

        // Get the conversation
        $conversation = Conversation::findOrFail($c_id);

        // Create the new message.
        $message = new Message;
        $message->user_id = Auth::id();
        $message->conversation_id = $conversation->id;
        $message->body = $request->get('body');
        $message->save();
        $message = $message->fresh();

        // Upload the files.
        (new Attachments($message))->upload('files');

        return $this->successResponse(
            'You have added a new message!',
            $request
        );
    }
}
