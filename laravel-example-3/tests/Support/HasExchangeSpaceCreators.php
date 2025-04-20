<?php

namespace Tests\Support;

use App\User;
use App\Message;
use Tests\Support\HasTestFiles;
use Illuminate\Support\Facades\Auth;
use App\Support\ExchangeSpaceStatusType;
use App\Support\ConversationCategoryType;
use App\ExchangeSpace;
use App\ExchangeSpaceMember;

trait HasExchangeSpaceCreators
{
    use HasTestFiles;

    /**
     * Adds a member to an exchange space.
     *
     * @param ExchangeSpace $space
     * @param boolean $isSellerAdvisor
     * @return ExchangeSpaceMember
     */
    protected function addExchangeSpaceAdvisor($space, $isSellerAdvisor = true, $user = null, $requestedById = null)
    {
        return factory('App\ExchangeSpaceMember')
        ->states($isSellerAdvisor ? 'seller-advisor' : 'buyer-advisor', 'approved')
        ->create([
            'exchange_space_id' => $space->id,
            'user_id' => is_null($user) ? factory('App\User')->create() : $user,
            'requested_by_id' => $requestedById
        ]);
    }

    /**
     * Creates a test conversation.
     *
     * @param array $conversationData
     * @param App\User $seller
     * @param App\User $buyer
     *
     * @return App\Conversation
     */
    protected function createSpaceConversation($conversationData = [], $seller = null, $buyer = null)
    {
        $conversation = $this->createInquiryConversation(
            $conversationData,
            $seller,
            $buyer
        );

        // Update the inquiry.
        $space = $conversation->space;
        $space->status = ExchangeSpaceStatusType::ACCEPTED;
        $space->save();
        $space->allMembers->each->activate();

        // Update the inquiry conversation.
        $conversation->resolved = $conversationData['resolved'] ?? true;
        $conversation->save();

        // Add an inital  message
        $this->addMessageToConversation($conversation, ['user_id' => $space->seller_user->id]);

        return $conversation->fresh();
    }

    /**
     * Creates a test conversation.
     *
     * @param array $conversationData
     * @param App\User $seller
     * @param App\User $buyer
     *
     * @return App\Conversation
     */
    protected function createInquiryConversation($conversationData = [], $seller = null, $buyer = null)
    {
        // Setup the required users.
        $seller = is_null($seller) ? factory('App\User')->create() : $seller;
        $buyer = is_null($buyer) ? factory('App\User')->create() : $buyer;

        // Setup the exchange space.
        $inquiry = factory('App\ExchangeSpace')->states('inquiry')->create([
            'user_id' => $seller->id,
        ]);

        // Add the seller to the exchange space.
        $seller = factory('App\ExchangeSpaceMember')->states('seller')->create([
            'exchange_space_id' => $inquiry->id,
            'user_id' => $seller->id,
        ]);

        // Add the buyer to the exchange space.
        $buyer = factory('App\ExchangeSpaceMember')->states('buyer')->create([
            'exchange_space_id' => $inquiry->id,
            'user_id' => $buyer->id,
        ]);

        // Finally...create the conversation and return it.
        $conversationData = array_merge(['is_inquiry' => true], $conversationData);
        return $this->addConversationToSpace($inquiry, $conversationData);
    }

    /**
     * Adds a conversation to a space.
     *
     * @param App\ExchangeSpace $space
     * @param array $conversationData
     * @return App\Conversation
     */
    protected function addConversationToSpace($space, $conversationData = [])
    {
        return factory('App\Conversation')->create(array_merge([
            'exchange_space_id' => $space->id,
        ], $conversationData));
    }

    /**
     * Adds a message to a conversation.
     *
     * @param App\ExchangeSpace $conversation
     * @param array $messageData
     * @return App\Message
     */
    protected function addMessageToConversation($conversation, $messageData)
    {
        return factory('App\Message')->create(array_merge([
            'conversation_id' => $conversation->id,
        ], $messageData));
    }

    /**
     * Creates all items for an exchange space.
     *
     * @param array $conversationData
     * @param App\User $seller
     * @param App\User $buyer
     *
     * @return App\ExchangeSpace
     */
    protected function createAcceptedExchangeSpace($conversationData = [], $seller = null, $buyer = null)
    {
        // Create the inquiry
        $inquiry = $this->createInitalInquiry(
            $conversationData,
            $seller,
            $buyer
        );

        $inquiry->status = ExchangeSpaceStatusType::ACCEPTED;
        $inquiry->save();
        $inquiry->allMembers->each->activate();

        return $inquiry->fresh();
    }

    /**
     * Creates all items for an initial inquiry.
     *
     * @param array $conversationData
     * @param App\User $seller
     * @param App\User $buyer
     *
     * @return App\ExchangeSpace
     */
    protected function createInitalInquiry($conversationData = [], $seller = null, $buyer = null)
    {
        // Create the inquiry conversation.
        $conversation = $this->createInquiryConversation(
            $conversationData,
            $seller,
            $buyer
        );

        // Create the message.
        $message = new Message;
        $message->user_id = auth()->id();
        $message->conversation_id = $conversation->id;
        $message->body = 'Start of business inquiry';
        $message->save();

        return $conversation->fresh()->space->fresh();
    }

    /**
     * Adds upload(s) to exchange space.
     *
     * @param App\Conversation $conversation
     * @param integer $count
     * @param string $type
     * @return App\ExchangeSpace
     */
    protected function addUploadsToConversation($conversation, $count = 1, $type = 'jpg')
    {
        $this->post(
            route('exchange-spaces.conversations.update', [
                'id' => $conversation->space->id,
                'c_id' => $conversation->id,
            ]),
            [
                'body' => $body = $this->faker->paragraphs(3, true),
                'files' => [
                    'new' => $files = $this->getTestFiles($count, $type),
                ],
            ]
        );

        return $conversation;
    }

    /**
     * Gets a random conversation category.
     *
     * @param array $notIn
     *
     * @return int
     */
    protected function getRandomConversationCategory($notIn = [])
    {
        $values = ConversationCategoryType::getValues();
        $notIn = array_merge([
            ConversationCategoryType::BUYER_INQUIRY,
        ], $notIn);

        foreach ($notIn as $key => $value) {
            $key = array_search($value, $values);
            if ($key !== false) {
                unset($values[ $key ]);
            }
        }

        return $this->faker->randomElement($values);
    }
}
