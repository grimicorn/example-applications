<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\UserEmailNotificationSettings;
use App\Jobs\SendUserDueDiligenceDigestEmails;
use App\Support\Notification\HasNotifications;
use App\Support\Notification\DueDiligenceDigestNotification;

class SendDueDiligenceDigest extends Command
{
    use HasNotifications;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fe:send-due-diligence-digest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends the Due Diligence Digest';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        UserEmailNotificationSettings::where('due_diligence_digest', true)
        ->with('user')
        ->get()->each(function ($settings) {
            if ($settings->user) {
                $this->dispatchNotification(new DueDiligenceDigestNotification($settings->user));
            }
        });
    }
}
