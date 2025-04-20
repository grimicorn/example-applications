<?php

namespace App\Support\Notification;

use App\Message;
use App\AbuseReportLink;

trait ReportsAbuse
{
    public function reportNotificationAbuseLink($type, $message, $reporterId, $creatorId = null, $extraData = [])
    {
        return $this->reportAbuseLink($message, $reporterId, $creatorId, $type, $extraData);
    }

    public function reportAbuseLink($message, $reporterId, $creatorId = null, $notificationType = null, $extraData = [])
    {
        $link = AbuseReportLink::firstOrCreate($data = [
            'notification_type' => $notificationType ?? '',
            'message' => $message,
            'reporter_id' => $reporterId,
            'creator_id' => $creatorId ?? auth()->id(),
        ]);

        return route('abuse-report.store', array_merge([
            'id' => $link->id,
        ], $extraData));
    }

    public function reportMessageAbuseLink(Message $message, $reporterId, $extraData = [])
    {
        $link = AbuseReportLink::firstOrCreate($data = [
            'notification_type' => NotificationType::MESSAGE,
            'message' => $message->body,
            'reporter_id' => $reporterId,
            'creator_id' => $message->user_id,
            'message_id' => $message->id,
            'reason' => null,
            'reason_details' => null,
            'message_model' => Message::class,
        ]);

        return route('abuse-report.store', array_merge([
            'id' => $link->id,
        ], $extraData));
    }
}
