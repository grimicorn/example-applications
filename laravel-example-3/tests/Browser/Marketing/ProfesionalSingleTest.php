<?php

namespace Tests\Browser\Marketing;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProfesionalSingleTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @test
     * @group failing
     */
    public function it_only_allows_logged_in_users_to_view_the_contact_form()
    {
        $this->browse(function (Browser $browser) {
            // Create a user to visit.
            $contactedUser = factory(User::class)->create();

            // Try to access the contact form when logged out.
            $browser->visit(route('professional.show', ['id' => $contactedUser->id]))
                           ->assertMissing('.marketing-professional-contact-form')
                           ->assertVisible('.professional-contact-login-btn');

            // Sign in a user and then make sure we can access the contact form.
            $browser->loginAs(factory(User::class)->create())
                           ->visit(route('professional.show', ['id' => $contactedUser->id]))
                           ->assertVisible('.marketing-professional-contact-form')
                           ->assertMissing('.professional-contact-login-btn');
        });
    }
}
