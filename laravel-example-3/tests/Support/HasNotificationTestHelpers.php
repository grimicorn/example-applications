<?php

namespace Tests\Support;

use App\Conversation;
use App\ExchangeSpace;
use App\ExchangeSpaceMember;
use App\Mail\NewNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use App\Support\Notification\NotificationType;
use App\SavedSearchNotification as SavedSearchNotificationModel;
use App\ConversationNotification as ConversationNotificationModel;
use App\ExchangeSpaceNotification as ExchangeSpaceNotificationModel;

trait HasNotificationTestHelpers
{
    protected function enableMemberEmailNotifications(Collection $members): void
    {
        $members->pluck('user')->map->emailNotificationSettings->each(function ($settings) {
            $settings->enable_all = true;
            $settings->enable_due_diligence = true;
            $settings->enable_login = true;
            $settings->due_diligence_digest = true;
            $settings->save();
        });
    }

    protected function disableMemberEmailNotifications(Collection $members): void
    {
        $members->pluck('user')->map->emailNotificationSettings->each(function ($settings) {
            $settings->enable_all = false;
            $settings->enable_due_diligence = false;
            $settings->enable_login = false;
            $settings->due_diligence_digest = false;
            $settings->save();
        });
    }

    protected function assertNotificationCount(int $count, int $type): void
    {
        $this->assertCount($count, $this->getEmailNotificationsByType($type));
    }

    protected function getAllMemberDatabaseNotifications($space, $user)
    {
        return ExchangeSpaceNotificationModel::where('exchange_space_id', $space->id)->where('user_id', $user->id)->get()
        ->concat(
            ConversationNotificationModel::whereIn('conversation_id', $space->conversations->pluck('id'))
            ->where('user_id', $user->id)->get()
        );
    }

    protected function getEmailNotificationsByType(int $type): Collection
    {
        return Mail::sent(NewNotification::class, function ($mailable) use ($type) {
            return $mailable->notification->type() === $type;
        });
    }

    protected function createsExchangeSpaceNotifications(ExchangeSpace $space, Conversation $conversation, array $skipTypes = [])
    {
        $skipTypes = collect($skipTypes);

        // Create some exchange space notifications
        collect($space->getSpaceNotificationTypes())->each(function ($type) use ($space, $skipTypes) {
            if (!$skipTypes->contains($type)) {
                $space->members->each(function ($member) use ($type, $space) {
                    factory('App\ExchangeSpaceNotification')->create([
                        'type' => $type,
                        'exchange_space_id' => $space->id,
                        'user_id' => $member->user->id,
                    ]);
                });
            }
        });

        // Create a conversation notification
        if (!$skipTypes->contains(NotificationType::NEW_DILIGENCE_CENTER_CONVERSATION)) {
            $space->members->each(function ($member) use ($space, $conversation) {
                factory('App\ConversationNotification')->create([
                    'user_id' => $space->seller_user->id,
                    'conversation_id' => $conversation->id,
                    'type' => NotificationType::NEW_DILIGENCE_CENTER_CONVERSATION,
                    'user_id' => $member->user->id,
                ]);
            });
        }

        // Create a message notification
        if (!$skipTypes->contains(NotificationType::MESSAGE)) {
            $space->members->each(function ($member) use ($space, $conversation) {
                factory('App\ConversationNotification')->create([
                    'user_id' => $space->seller_user->id,
                    'conversation_id' => $conversation->id,
                    'type' => NotificationType::MESSAGE,
                    'user_id' => $member->user->id,
                ]);
            });
        }
    }

    /**
     * Assert if the members have notifications in database.
     *
     * @param \Illuminate\Database\Eloquent\Collection $members
     * @param int $type
     * @param array $data
     * @return void
     */
    protected function assertMembersNotificationInDatabase($members, $type, $data = [])
    {
        $members->each(function ($member) use ($type, $data) {
            $this->assertDatabaseHas(
                'exchange_space_notifications',
                $this->assertDatabaseFields($member, $type, $data)
            );
        });
    }

    /**
     * Assert if the members does NOt have a notification in database.
     *
     * @param \Illuminate\Database\Eloquent\Collection $members
     * @param int $type
     * @return void
     */
    protected function assertMembersNotificationDatabaseMissing($members, $type)
    {
        $members->map(function ($member) use ($type) {
            $space = $member->space()->withTrashed()->first();

            $this->assertTrue(
                \App\ExchangeSpaceNotification::where('user_id', $member->user->id)
                ->where('exchange_space_id', $space->id)
                ->where('type', $type)->get()->isEmpty()
            );
        });
    }

    /**
     * Undocumented function
     *
     * @param App\ExchangeSpaceMember $member
     * @param int $type
     * @param array $data
     * @return void
     */
    protected function assertDatabaseFields(ExchangeSpaceMember $member, $type, $data = [])
    {
        $space = $member->space()->withTrashed()->first();

        return collect([
            'user_id' => $member->user_id,
            'exchange_space_id' => $space->id,
            'type' => $type,
            'exchange_space_deal' => $space->deal,
            'exchange_space_status' => $space->status,
        ])->merge($data)->filter()->toArray();
    }
}
