<?php
$router->group(['middleware' => 'developer', 'prefix' => 'mailable'], function ($router) {
    $router->get('/test-send', 'MailableTestSendController@index');

    $router->view('/list', 'mailable.list')->name('mailable.list');

    // Conversation Abuse Reported
    $router->get('/abuse-report', function () {
        $link = App\AbuseReportLink::inRandomOrder()->take(1)->whereNull('message_id')->get()->first();

        if (!$link) {
            $link = factory('App\AbuseReportLink')->create([
                'message_id' => null,
                'reason' => null,
                'reason_details' => null,
                'message_model' => null,
            ]);
        }

        return new \App\Mail\AbuseReported($link);
    })->name('mailable.abuse-report');

    // Conversation Abuse Reported
    $router->get('/conversation-abuse-reported', function () {
        $link = App\AbuseReportLink::inRandomOrder()->take(1)->whereNotNull('message_id')->get()->first();

        if (!$link) {
            $link = factory('App\AbuseReportLink')->create([
                'notification_type' => \App\Support\Notification\NotificationType::MESSAGE,
            ]);
        }

        return new \App\Mail\AbuseReported($link);
    })->name('mailable.conversation-abuse-reported');

    // Marketing Contact Received
    $router->get('/marketing-contact-received', function () {
        return new \App\Mail\MarketingContactReceived([
            'name' => 'First Last',
            'phone' => '(111) 111-1111',
            'email' => 'emailusername@test.com',
            'preferred_contact_method' => array_random(['Phone', 'Email']),
            'message' => 'In eget ligula id ex semper consectetur. Proin pulvinar placerat varius. Nunc vel elementum risus. Praesent efficitur maximus ultricies. Quisque lobortis dui ultricies orci aliquet scelerisque. Vivamus sit amet nisi laoreet erat finibus commodo. Duis vitae sapien tincidunt nulla sollicitudin sollicitudin vel fringilla eros.',
        ]);
    })->name('mailable.marketing-contact-received');

    // Notification
    $router->get('/notification/{slug}/{id?}', function ($slug, $id = null) {
        return (new \App\Support\Notification\MailableExample($slug, $id))->get();
    })->name('mailable.notification');
});
