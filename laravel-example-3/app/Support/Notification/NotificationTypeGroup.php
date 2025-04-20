<?php
/**
 * Created by PhpStorm.
 * User: djacobsmeyer
 * Date: 9/27/17
 * Time: 4:12 PM
 */
namespace App\Support\Notification;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use App\SavedSearchNotification as SavedSearchNotificationModel;
use App\ConversationNotification as ConversationNotificationModel;
use App\ExchangeSpaceNotification as ExchangeSpaceNotificationModel;

abstract class NotificationTypeGroup
{
    const NO_MODEL = 1;
    const EXCHANGE_SPACE = 2;
    const CONVERSATION = 3;
    const SAVED_SEARCH = 4;

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
     * Get the notification type group models.
     *
     * @return array
     */
    public static function getModels(): Collection
    {
        return collect([
            self::NO_MODEL => null,
            self::CONVERSATION => ConversationNotificationModel::class,
            self::EXCHANGE_SPACE => ExchangeSpaceNotificationModel::class,
            self::SAVED_SEARCH => SavedSearchNotificationModel::class,
        ]);
    }

    /**
     * Gets the notification type group model
     *
     * @param int $type
     * @return Model|null
     */
    public static function getModel(int $type): ?Model
    {
        return self::getModels()->get($type);
    }

    /**
     * Gets the group by its model
     *
     * @param Model $model
     * @return string|null
     */
    public static function getGroupByModel(?string $model): ?string
    {
        $group = self::getModels()->search($model);

        return $group ? $group : self::NO_MODEL;
    }
}
