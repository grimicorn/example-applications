<?php

namespace App\Support\Notification;

use App\Conversation;
use App\Support\Notification\Notification;
use App\Support\Notification\NotificationType;
use App\Support\Notification\ReportsAbuse;
use App\ConversationNotification as ConversationNotificationModel;

class ConversationNotification extends Notification
{
    use ReportsAbuse;

    protected $conversation;
    protected $space;
    protected $member;
    protected $message;

    public function __construct(Conversation $conversation, $type, $data = [])
    {
        parent::__construct($type, $data);
        $this->conversation = $conversation;
        $this->message = optional($this->conversation->messages()->latest()->take(1)->first());
    }

    /**
     * {@inheritDoc}
     */
    public function save()
    {
        $member = $this->recipientMember();

        ConversationNotificationModel::create(array_merge([
            'user_id' => $this->recipient()->id,
            'conversation_id' => $this->conversation->id,
            'type' => $this->type,
            'message_sender_name' => auth()->user()->name,
            'conversation_title' => $this->conversation->title,
            'exchange_space_title' => $member->custom_title,
        ], $this->data));
    }

    /**
     * {@inheritDoc}
     */
    public function emailBody()
    {
        $viewSlug = $this->getViewSlugFromType();
        $member = $this->recipientMember();
        $message = $this->message;
        $listing = optional($member->space)->listing;
        $sender = optional($message->user);
        $space = optional($member->space);

        return view("app.sections.notifications.email.{$viewSlug}", [
            'exchange_space' => $space,
            'listing_title' => $listing->title,
            'conversation' => $this->conversation,
            'conversation_title' => optional($this->conversation)->title,
            'conversation_url' => $this->conversation->show_url,
            'exchange_space_title' => $member->custom_title,
            'is_inquiry' => !$space->accepted(),
            'message' => $message,
            'message_body' => $message->body,
            'sender_name' => $sender->name,
            'sender_profile_url' => $sender->profile_url,
            'report_abuse_link' => $this->abuseLink(),
        ]);
    }

    protected function abuseLink()
    {
        if ($this->type === NotificationType::MESSAGE) {
            return $this->message->reportAbuseUrl($this->recipient());
        }

        return $this->reportNotificationAbuseLink(
            $this->type,
            $this->message->body,
            $this->recipient()->id
        );
    }

    /**
     * {@inheritDoc}
     */
    public function emailSubject()
    {
        $member = $this->recipientMember();
        return NotificationType::emailSubject($this->type, [
            'space' => $member->space,
            'conversation' => $this->conversation,
            'member' => $member,
        ]);
    }

    /**
     * If the notification should send an email.
     *
     * @return boolean
     */
    public function shouldEmail()
    {
        if (!$this->shouldNotify()) {
            return false;
        }

        // If the do not have the notifications set then we are done.
        $settings = optional($this->recipient()->emailNotificationSettings);
        if (!$settings->enable_due_diligence) {
            return false;
        }

        return !$settings->due_diligence_digest;
    }

    public function shouldNotify()
    {
        $member = $this->recipientMember();

        // Don't send notifications for the inital business inquiry.
        if ($this->type === NotificationType::NEW_DILIGENCE_CENTER_CONVERSATION) {
            return !$member->space->is_inquiry;
        }

        return $this->conversation->messages()->get()->count() > 1;
    }

    protected function recipientMember()
    {
        return $this->conversation->space->members()->where('user_id', $this->recipient()->id)->first();
    }
}
