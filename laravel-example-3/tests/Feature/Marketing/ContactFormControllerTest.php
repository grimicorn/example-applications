<?php

namespace Tests\Feature\Marketing;

use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use App\Mail\MarketingContactReceived;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

// @codingStandardsIgnoreStart
class ContactFormControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_sends_the_admin_a_new_contact_notification_email()
    {
        // Setup
        Mail::fake();

        $fields = $this->getFields();

        // Execute
        $response = $this->post('/contact', $fields);

        // Assert
        $response->assertStatus(302);

        Mail::assertSent(MarketingContactReceived::class, function ($mail) use ($fields) {
            $this->assertEquals($fields, $mail->fields);
            $this->assertEquals('support@firmexchange.com', $mail->to[0]['address']);

            return true;
        });
    }

    /**
     * @test
     */
    public function it_stores_the_contact_request()
    {
        // Setup
        $fields = $this->getFields();

        // Execute
        $response = $this->post('/contact', $fields);

        // Assert
        $response->assertStatus(302);

        $this->assertDatabaseHas('marketing_contact_notifications', $fields);
    }

    /**
     * @test
     */
    public function it_redirects_with_a_success_message_after_successful_submission()
    {
        // Setup
        $fields = $this->getFields();

        // Execute
        $response = $this->post('/contact', $fields);

        // Assert
        $response->assertStatus(302)
                 ->assertSessionHas('status')
                 ->assertSessionHas('success', true);
    }

    /**
     * @test
     */
    public function it_validates_the_submission()
    {
        // Setup
        $fields = $this->getFields();
        $fields['name'] = '';
        $fields['phone'] = '';
        $fields['email'] = 'notanemail';

        // Execute
        $response = $this->post('/contact', $fields);

        // Assert
        $response->assertStatus(302)
                 ->assertSessionHasErrors(['name', 'phone', 'email']);
    }

    /**
     * Gets the testing fields.
     *
     * @param  array  $data
     *
     * @return array
     */
    protected function getFields($data = [])
    {
        return collect([
            'name' => $this->faker->name,
            'phone' => $this->faker->numerify('##########'),
            'email' => $this->faker->email,
            'preferred_contact_method' => $this->faker->name,
            'message' => $this->faker->paragraphs(3, true),
        ])->merge($data)->toArray();
    }
}
