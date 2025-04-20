<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\AbuseReportLink;
use App\Mail\AbuseReported;
use Illuminate\Support\Facades\Mail;
use App\Support\Notification\ReportsAbuse;
use Tests\Support\HasExchangeSpaceCreators;
use Illuminate\Foundation\Testing\WithFaker;
use App\Support\Notification\NotificationType;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreFile
class AbuseReportControllerTest extends TestCase
{
    use RefreshDatabase,
        HasExchangeSpaceCreators;

    /**
    * @test
    */
    public function it_records_an_email_notification_abuse_report()
    {
        $link = factory('App\AbuseReportLink')->states('no_model')->create();

        $this->assertNull($link->reported_on);

        $this->get(route('abuse-report.store', [
            'id' => $link->id,
        ]))->assertRedirect(route('home'));

        $this->assertNotNull($link->fresh()->reported_on);
    }

    /**
    * @test
    */
    public function it_notifies_support_about_the_email_notification_abuse_report()
    {
        Mail::fake();

        $link = factory('App\AbuseReportLink')->states('no_model')->create();

        $response = $this->get(route('abuse-report.store', [
            'id' => $link->id,
        ]));

        Mail::assertSent(AbuseReported::class, function ($mail) use ($link) {
            return intval($mail->link->notification_type) === intval($link->notification_type) &&
            $mail->link->message === $link->message &&
            $mail->link->reporter_id === $link->reporter_id &&
            $mail->link->creator_id === $link->creator_id &&
            $mail->hasTo('support@firmexchange.com');
        });
    }

    /**
    * @test
    */
    public function it_stores_unique_abuse_report_links_when_requesting_a_link()
    {
        $trait = $this->getMockForTrait(ReportsAbuse::class);

        $data = collect(factory('App\AbuseReportLink')->make())->only([
            'message',
            'reporter_id',
            'creator_id',
            'notification_type',
        ])->toArray();

        $message = $data['message'];
        $reporterId = $data['reporter_id'];
        $creatorId = $data['creator_id'];
        $notificationType = $data['notification_type'];

        // At this point we should not have any records...
        $this->assertCount(0, AbuseReportLink::where($data)->get());

        // Make the link once it should create a record...
        $link = $trait->reportAbuseLink($message, $reporterId, $creatorId, $notificationType);
        $this->assertCount(1, AbuseReportLink::where($data)->get());

        // Make the same link again it should not create a record.
        $link = $trait->reportAbuseLink($message, $reporterId, $creatorId, $notificationType);
        $this->assertCount(1, AbuseReportLink::where($data)->get());
    }

    /**
     * @test
     */
    public function it_reports_conversation_message_abuse_from_notification()
    {
        $reporter = $this->signInWithEvents();
        $conversation = $this->createInquiryConversation([], $reporter);
        $message = $this->addMessageToConversation($conversation, [
            'user_id' => $conversation->space->buyer_user->id,
        ]);

        $this->assertEmpty(AbuseReportLink::all());

        Mail::fake();

        $this->get($message->reportAbuseUrl($reporter))
        ->assertRedirect($message->conversation->show_url);

        $link = optional(AbuseReportLink::latest()->take(1)->first());

        $this->assertNotNull($link->reported_on);

        Mail::assertSent(AbuseReported::class, function ($mail) use ($link) {
            return $mail->link->notification_type === $link->notification_type &&
                $mail->link->message === $link->message &&
                $mail->link->reporter_id === $link->reporter_id &&
                $mail->link->creator_id === $link->creator_id &&
                $mail->link->notification_type === NotificationType::MESSAGE &&
                $mail->link->message_id === $link->message_id &&
                $mail->link->message_model === $link->message_model &&
                $mail->hasTo('support@firmexchange.com');
        });
    }

    /**
     * @test
     * @group failing
     */
    public function it_reports_conversation_abuse_in_diligence_center()
    {
        $reporter = $this->signInWithEvents();
        $conversation = $this->createInquiryConversation([], $reporter);
        $message = $this->addMessageToConversation($conversation, [
            'user_id' => $conversation->space->buyer_user->id,
        ]);

        $this->assertEmpty(AbuseReportLink::all());

        Mail::fake();

        $this->post($message->reportAbuseUrl(
            $reporter,
            $reason = 'Reason',
            $reason_details = 'Reason Details'
        ))->assertRedirect($message->conversation->show_url);

        $link = optional(AbuseReportLink::latest()->take(1)->first());

        $this->assertNotNull($link->reported_on);

        Mail::assertSent(AbuseReported::class, function ($mail) use ($link, $reason, $reason_details) {
            return $mail->link->notification_type === $link->notification_type &&
                $mail->link->message === $link->message &&
                $mail->link->reporter_id === $link->reporter_id &&
                $mail->link->creator_id === $link->creator_id &&
                $mail->link->notification_type === NotificationType::MESSAGE &&
                $mail->link->message_id === $link->message_id &&
                $mail->link->reason === $reason &&
                $mail->link->reason_details === $reason_details &&
                $mail->link->message_model === $link->message_model &&
                $mail->hasTo('support@firmexchange.com');
        });
    }
}
