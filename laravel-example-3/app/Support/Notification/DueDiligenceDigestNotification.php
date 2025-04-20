<?php

namespace App\Support\Notification;

use App\User;
use App\Support\Notification\Notification;
use App\Support\Notification\NotificationType;

class DueDiligenceDigestNotification extends Notification
{
    protected $user;
    protected $conversations;

    public function __construct(User $user, $data = [])
    {
        $this->user = $user;
        $this->conversations = $this->getDigestConversations();

        parent::__construct(NotificationType::DUE_DILIGENCE_DIGEST);

        $this->setRecipient($this->user);
    }

    /**
     * {@inheritDoc}
     */
    public function save()
    {
        // This notification will not be saved.
    }

    /**
     * {@inheritDoc}
     */
    public function emailBody()
    {
        return view('app.sections.notifications.email.due-diligence-digest', [
            'user' => $this->user,
            'summary_items' => $this->conversations->groupBy('exchange_space_id')->map(function ($group) {
                $firstConversation = optional($group->first());
                $space = optional($firstConversation->space()->withTrashed()->get()->first());
                return collect([
                    'space' => $space,
                    'conversations' => $group,
                    'member' => optional($space->members->where('user_id', $this->recipient()->id)->first())
                ]);
            }),
            'dashboard_url' => route('dashboard'),
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function shouldEmail()
    {
        return !$this->conversations->isEmpty();
    }

    protected function getDigestConversations()
    {
        return $this->user->spaceActiveMembers->map(function ($member) {
            return $member->space->conversations()
            ->where('updated_at', '>=', $this->lastSent())
            ->where('updated_at', '<=', now())
            ->get()
            ->filter(function ($conversation) {
                return !$conversation->messages()
                ->where('updated_at', '>=', $this->lastSent())
                ->where('updated_at', '<=', now())
                ->where('user_id', '!=', $this->user->id)
                ->get()
                ->isEmpty();
            });
        })->flatten(1);
    }

    protected function lastSent()
    {
        return $this->user->dueDiligenceDigestLastSent();
    }

    /**
     * The email subject.
     *
     * @return string
     */
    public function emailSubject()
    {
        return NotificationType::emailSubject($this->type, ['recipient' => $this->recipient()]);
    }

    /**
     * Get the value of conversations
     */
    public function getConversations()
    {
        return $this->conversations;
    }
}
