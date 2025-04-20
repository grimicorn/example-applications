<?php

use Tests\TestCase;
use App\Mail\Support;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SupportContactControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_sends_the_support_email()
    {
        // Setup
        $user = new \stdClass;
        $user->email = config('mail.to');
        $user->name = config('mail.name');

        Mail::fake();

        // Execute
        $data = [
            'name' => 'Test',
            'email' => 'test@test.com',
            'message' => 'Test message',
        ];
        $response = $this->post('/support', $data);

        // Assert
        $this->assertEquals(302, $response->status());
        $this->withSession(['form_success', 'Thank you for contacting us, we will be in touch soon.']);

        Mail::assertSent(Support::class, function ($mail) use ($data) {
            return $mail->data['name'] === $data['name']
                   && $mail->data['email'] === $data['email']
                   && $mail->data['message'] === $data['message'];
        });

        Mail::assertSent(Support::class, function ($mailable) use ($user) {
            return $mailable->hasTo($user->email);
        });
    }

    /**
     * @test
     */
    public function it_requires_a_name()
    {
        // Execute
        $data = [
            'email' => 'test@test.com',
            'message' => 'Test message',
        ];
        $response = $this->post('/support', $data);

        // Assert
        $this->assertEquals(302, $response->status());
        $this->withSession(['error', 'The first name field is required.']);
    }

    /**
     * @test
     */
    public function it_requires_an_email()
    {
        // Execute
        $data = [
            'name' => 'Test',
            'message' => 'Test message',
        ];
        $response = $this->post('/support', $data);

        // Assert
        $this->assertEquals(302, $response->status());
        $this->withSession(['error', 'The email field is required.']);
    }
}
