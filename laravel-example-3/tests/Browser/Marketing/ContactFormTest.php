<?php

namespace Tests\Browser\Marketing;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

// @codingStandardsIgnoreStart
class ContactFormTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function a_success_alert_is_displayed_when_a_user_successfully_submits_the_contact_form()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/contact')
                          ->value('input[name="name"]', $this->faker->name)
                          ->value('input[name="phone"]', $this->faker->numerify('##########'))
                          ->value('input[name="email"]', $this->faker->safeEmail)
                          ->click($this->faker->randomElement([
                              'label[for="preferred_contact_method_1"]',
                              'label[for="preferred_contact_method_2"]',
                          ]))
                          ->value('textarea[name="message"]', $this->faker->paragraph)
                          ->click('button.fe-form-submit')
                          ->waitForLocation('/contact')
                          ->assertSeeIn('.alert-success', 'Your message has been sent and we will be in touch soon!');
        });
    }

    /**
     * @test
     */
    public function a_user_is_notified_when_errors_are_present_the_contact_form_submission()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/contact')
                          ->click('button.fe-form-submit')
                          ->waitForLocation('/contact')
                          ->assertSeeIn('.alert-danger', 'our submission contains error(s) highlighted below.')
                          ->assertSee('The Name field is required.')
                          ->assertSee('The Phone field is required.')
                          ->assertSee('The Email field is required.');
        });
    }
}
