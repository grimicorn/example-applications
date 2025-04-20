<?php

namespace Tests\Feature\Marketing;

use App\User;
use Tests\TestCase;
use App\Mail\ProfessionalContacted;
use Illuminate\Support\Facades\Mail;
use Tests\Support\HasNotificationTestHelpers;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Support\Notification\NotificationType;

class ProfessionalContactFormTest extends TestCase
{
    use RefreshDatabase,
    HasNotificationTestHelpers;

    /**
     * @test
     */
    public function it_sends_the_professional_contact_email()
    {
        // Start mail faking so we can check it later.
        Mail::fake();

        // Sign in a user to all them to submit the form
        $contactUser = $this->signInWithEvents();

        // Create a user so the contact user can contact them.
        $contactedUser = factory(User::class)->create();

        // Post the contact form.
        $submission = [
            'name' => $name = $this->faker->name(),
            'phone' => $phone = $this->faker->phoneNumber,
            'message' => $message = $this->faker->paragraphs(3, true),
            'email' => $email = $this->faker->email,
        ];
        $response = $this->post(route('professional.contact.store', ['id' => $contactedUser->id]), $submission);

        // Make sure the form was submitted okay.
        $response->assertStatus(302)
        ->assertSessionHas('status')
        ->assertSessionHas('success', true);

        // Make sure the email was sent.
        $this->assertNotificationCount(1, NotificationType::PROFESSIONAL_CONTACTED);
    }
}
