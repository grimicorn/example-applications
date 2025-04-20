<?php
/**
 * Created by PhpStorm.
 * User: djacobsmeyer
 * Date: 9/27/17
 * Time: 4:12 PM
 */
namespace App\Support\Notification;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Support\Notification\NotificationMessage;
use App\Support\Notification\NotificationTypeGroup;
use App\SavedSearchNotification as SavedSearchNotificationModel;
use App\ConversationNotification as ConversationNotificationModel;
use App\ExchangeSpaceNotification as ExchangeSpaceNotificationModel;

/**
 * @see /how-to-add-a-notification.md
 */
abstract class NotificationType
{
    const SAVED_SEARCH_MATCH = 1;
    const MESSAGE = 2;
    const ADDED_ADVISOR_BUYER = 3;
    const ADDED_ADVISOR_SELLER = 4;
    const NEW_BUYER_INQUIRY = 5;
    const NEW_MEMBER = 6;
    const NEW_EXCHANGE_SPACE = 7;
    const DELETED_EXCHANGE_SPACE = 8;
    const STATUS_CHANGED = 9;
    const DEAL_UPDATED = 10;
    const LOGIN = 11;
    const NEW_MEMBER_REQUESTED = 12;
    const HISTORICAL_FINANCIAL_AVAILABLE = 13;
    const HISTORICAL_FINANCIAL_UNAVAILABLE = 14;
    const NEW_USER = 15;
    const NEW_DILIGENCE_CENTER_CONVERSATION = 16;
    const DEAL_STAGE_NDA = 17;
    const RESET_PASSWORD = 18;
    const REJECTED_INQUIRY = 19;
    const REMOVED_ADVISOR_BUYER = 20;
    const REMOVED_ADVISOR_SELLER = 21;
    const CLOSED_ACCOUNT = 22;
    const REMOVED_BUYER = 23;
    const SELLER_REMOVED_ADVISOR = 24;
    const DUE_DILIGENCE_DIGEST = 25;
    const EXCHANGE_SPACE_MEMBER_WELCOME = 26;
    const PROFESSIONAL_CONTACTED = 27;
    const SAVED_SEARCH_MATCH_DIGEST = 28;

    /**
     * Gets the class constants
     *
     * @return Collection
     */
    public static function getConstants(): Collection
    {
        return collect((new \ReflectionClass(__CLASS__))->getConstants());
    }

    /**
     * Gets the notification type slugs
     *
     * @return Collection
     */
    public static function getSlugs(): Collection
    {
        return self::getConstants()->flip()->map(function ($constant) {
            return str_slug($constant);
        });
    }

    /**
     * Gets the notification type slug.
     *
     * @param integer $type
     * @return string
     */
    public static function getSlug(int $type): string
    {
        return self::getSlugs()->get($type, '');
    }

    /**
     * Gets the notification type labels.
     *
     * @return Collection
     */
    public static function getLabels(): Collection
    {
        return self::getConstants()->flip()->map(function ($constant) {
            return title_case(str_replace('_', ' ', $constant));
        });
    }

    /**
     * Gets the notification type label.
     *
     * @type int $type
     * @return string|null
     */
    public static function getLabel(int $type): ?string
    {
        return self::getLabels()->get($type);
    }

    /**
     * Get the notification type models.
     *
     * @return array
     */
    public static function getModels(): Collection
    {
        return collect([
            self::SAVED_SEARCH_MATCH => SavedSearchNotificationModel::class,
            self::MESSAGE => ConversationNotificationModel::class,
            self::ADDED_ADVISOR_BUYER => ExchangeSpaceNotificationModel::class,
            self::ADDED_ADVISOR_SELLER => ExchangeSpaceNotificationModel::class,
            self::NEW_BUYER_INQUIRY => ExchangeSpaceNotificationModel::class,
            self::NEW_MEMBER => ExchangeSpaceNotificationModel::class,
            self::NEW_EXCHANGE_SPACE => ExchangeSpaceNotificationModel::class,
            self::DELETED_EXCHANGE_SPACE => ExchangeSpaceNotificationModel::class,
            self::STATUS_CHANGED => ExchangeSpaceNotificationModel::class,
            self::DEAL_UPDATED => ExchangeSpaceNotificationModel::class,
            self::LOGIN => null,
            self::NEW_MEMBER_REQUESTED => ExchangeSpaceNotificationModel::class,
            self::HISTORICAL_FINANCIAL_AVAILABLE => ExchangeSpaceNotificationModel::class,
            self::HISTORICAL_FINANCIAL_UNAVAILABLE => ExchangeSpaceNotificationModel::class,
            self::NEW_USER => null,
            self::NEW_DILIGENCE_CENTER_CONVERSATION => ConversationNotificationModel::class,
            self::DEAL_STAGE_NDA => ExchangeSpaceNotificationModel::class,
            self::RESET_PASSWORD => null,
            self::REJECTED_INQUIRY => ExchangeSpaceNotificationModel::class,
            self::REMOVED_ADVISOR_BUYER => ExchangeSpaceNotificationModel::class,
            self::REMOVED_ADVISOR_SELLER => ExchangeSpaceNotificationModel::class,
            self::CLOSED_ACCOUNT => null,
            self::REMOVED_BUYER => ExchangeSpaceNotificationModel::class,
            self::SELLER_REMOVED_ADVISOR => ExchangeSpaceNotificationModel::class,
            self::DUE_DILIGENCE_DIGEST => null,
            self::EXCHANGE_SPACE_MEMBER_WELCOME => ExchangeSpaceNotificationModel::class,
            self::PROFESSIONAL_CONTACTED => null,
            self::SAVED_SEARCH_MATCH_DIGEST => null,
        ]);
    }

    /**
     * Gets the notification model
     *
     * @param int $type
     * @return Model|null
     */
    public static function getModel(int $type): ?string
    {
        return self::getModels()->get($type);
    }

    /**
     * Gets the notificaitons
     *
     * @param Model $notification
     * @return NotificationMessage
     */
    public static function getMessage(Model $notification): NotificationMessage
    {
        return (new NotificationMessage($notification));
    }

    /**
     * Gets the email subject for the notification
     *
     * @param integer $type
     * @param array $data
     * @return string
     */
    public static function emailSubject(int $type, array $data = []): string
    {
        return (new NotificationEmailSubject($type, $data))->get();
    }

    /**
     * Get all notificaiton groups.
     *
     * @return Collection
     */
    public static function getNotificationGroups(): Collection
    {
        $groups = collect([]);

        foreach (self::getModels() as $type => $model) {
            $groupKey = NotificationTypeGroup::getGroupByModel($model);
            $group = $groups->get($groupKey, collect([]));
            $group->push($type);
            $groups->put($groupKey, $group);
        }

        return $groups->sortBy(function ($group, $key) {
            return $key;
        });
    }

    /**
     * Get a notification group.
     *
     * @param integer $group
     * @return array
     */
    public static function getNotificationGroup(int $group): array
    {
        return self::getNotificationGroups()->get($group, collect([]))->toArray();
    }

    public static function getGroupKey(int $type): ?int
    {
        return optional(self::getNotificationGroups()->filter(function ($group) use ($type) {
            return $group->contains($type);
        })->keys())->first();
    }

    /**
     * Updates all of the notification table type columns
     *
     * @return void
     */
    public static function updateTableColumns()
    {
        collect([
            'saved_search_notifications',
            'exchange_space_notifications',
            'conversation_notifications',
        ])->each(function ($table) {
            self::columnUpdateMigration($table);
        });
    }

    /**
     * The notification type column migration
     *
     * @param string $table
     * @return void
     */
    public static function columnUpdateMigration(string $table)
    {
        if (app()->environment('testing')) {
            return;
        }

        $types = collect(self::getConstants())->map(function ($value) {
            return "'{$value}'";
        })->implode(',');
        DB::statement("ALTER TABLE {$table} CHANGE type type ENUM({$types})");
    }
}
