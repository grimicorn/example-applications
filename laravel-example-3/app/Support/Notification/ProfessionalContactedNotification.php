<?php

namespace App\Support\Notification;

use App\User;
use App\Support\Notification\Notification;
use App\Support\Notification\NotificationType;
use App\Support\Notification\ReportsAbuse;

class ProfessionalContactedNotification extends Notification
{
    use ReportsAbuse;

    public function __construct(User $recipient, $fields = [])
    {
        parent::__construct(NotificationType::PROFESSIONAL_CONTACTED);

        $this->fields = collect($fields);
        $this->setRecipient($recipient);
    }

    /**
     * {@inheritDoc}
     */
    public function save()
    {
        // This is an odd notification that will not be saved.
    }

    /**
     * {@inheritDoc}
     */
    public function emailBody()
    {
        return view('app.sections.notifications.email.professional-contacted', [
            'sender_name' => $this->fields->get('name'),
            'sender_phone' => $this->senderPhone(),
            'sender_email' => $this->fields->get('email'),
            'message' => $message = $this->fields->get('message'),
            'report_abuse_link' => $this->reportNotificationAbuseLink(
                $this->type,
                $message,
                $this->recipient()->id
            ),
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function shouldEmail()
    {
        return true;
    }

    protected function senderPhone()
    {
        $phone = $this->fields->get('phone');
        if (!$phone) {
            return '';
        }

        // Removes country code ex: +1
        $phone = trim(preg_replace('/\\+[0-9]+\\s/um', '', $phone));

        // Removes non-numeric characters.
        $phone = preg_replace('/[^0-9]/um', '', $phone);

        return collect([
            '(' . substr($phone, 0, 3) . ') ',
            substr($phone, 3, 3),
            '-',
            substr($phone, 6, 4),
        ])->implode('');
    }
}
