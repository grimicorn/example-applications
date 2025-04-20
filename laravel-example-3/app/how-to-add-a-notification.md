# How to add a notification

Due to some concerns about storing too much textual content in the database there was a design decision to not user Laravel's built in notification system.
So the steps will help you add a new notification type to the system.

### How to view notifications
- You can view the dashboard notifications here  (/dashboard/styleguide/notifications)
- You can view the list of email notifcations here (/mailable/list)

### How to add a new notification (EXAMPLE: Removed Advisor Buyer)
1. Add a constant to App\Support\Notification\NotificationType (EXAMPLE: REMOVED_ADVISOR_BUYER)
2. Add a key/value to the App\Support\Notification\NotificationType::getModels method to match the type to the model where it will be saved. Use null if the notificaiton is not saved. (EXAMPLE: App\Support\Notification\NotificationType::REMOVED_ADVISOR_BUYER => ExchangeSpaceNotificationModel::class)
3. If the notification does not already have a suitable notification class add a new class to app/Support/Notification that extends App\Support\Notification\Notification
    - The class would be RemovedAdvisorBuyerNotification but in this case we can just use the App\Support\Notification\ExchangeSpaceNotification since this is an exchange space notification
    - You will need to define the required abstract methods and also will want to review App\Support\Notification\Notification since it does a lot of the work for you
    - You also should create a matching test
4. Add an email content file to resources/views/app/sections/notifications/email/ using the slug name (EXAMPLE: removed-advisor-buyer.blade.php)
5. Add the email content to the previously created file. Do not include greeting/thanks section this will be handled the same every time.
6. Add a subject to the switch statement in App\Support\Notification\NotificationEmailSubject::get
(EXAMPLE:
 case NotificationType::REMOVED_ADVISOR_BUYER:
    return "A buyer advisor has left an Exchange Space";
    break;
)
7. If storing the notification in the database
    - Add a key/value to the App\Support\Notification\NotificationMessage::getBody method (EXAMPLE: NotificationType::REMOVED_ADVISOR_BUYER => $this->getRemovedAdvisorBuyerMessage()) with the dashboard notification message
    - Add a key/value to the App\Support\Notification\NotificationMessage::getIcon method (EXAMPLE: NotificationType::REMOVED_ADVISOR_BUYER => 'fa-pencil-square-o') with the dashboard notification icon class
    - Add a new migration  with App\Support\Notification\NotificationType::updateTableColumns see the 2018_01_16_203728_add_new_notification_types_to_tables.php migration for an example
8. Add mailable example to the App\Support\Notification\MailableExample::getMailableExampleMethods with either a current method name or a custom method if a matching mailable example does not exist (EXAMPLE: NotificationType::REMOVED_ADVISOR_BUYER => 'exchangeSpaceMemberMailable'). You can then view the notification via the matching link on /mailable/list
9. Dispatching a notification
    - Requires the App\Support\Notification\HasNotifications on the dispatcher
    - Use the dispatchNotification method with a new instance of your notification $this->dispatchNotification(new ExchangeSpaceNotification($space, NotificationType::REMOVED_ADVISOR_BUYER));
