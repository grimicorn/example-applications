<?php

namespace App\Notifications;

use App\Site;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SiteNeedsReview extends Notification implements ShouldQueue
{
    use Queueable;

    public $site;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Site $site)
    {
        $this->site = $site;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if (!$this->site->needs_review) {
            return [];
        }

        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)->markdown('site-needs-review', [
            'site' => $this->site->load('pages')->load('pages.snapshotConfigurations'),
            'pages' => $this->site->needsReviewPages(),
            'review_url' => $this->site->reviewUrl(),
        ])->subject("{$this->site->name} snapshots need review.");
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'site' => $this->site,
            'needs_review_pages' => $this->site->needsReviewPages(),
        ];
    }

    public function toDatabase()
    {
        return [
            'site_id' => $this->site->id,
            'needs_review_page_ids' => $this->site->needsReviewPages()->pluck('id'),
        ];
    }
}
