<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

// phpcs:ignorefile
class AuthTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
    * @test
    */
    public function it_authenticates_a_user()
    {
        $this->browse(function (Browser $browser) {
            $user = create(User::class, [
                'password' => bcrypt($password = uniqid()),
            ]);

            $browser->visit('/login');
            $browser->type('@login_email', $user->email);
            $browser->type('@login_password', $password);
            $browser->check('@login_remember_me');
            $browser->press('@login_submit');
            $browser->waitForReload();
            $browser->assertRouteIs('sites.index');
        });
    }

    /**
    * @test
    */
    public function it_displays_authentication_validation_errors()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('login'));
            $browser->press('@login_submit');
            $browser->waitFor('@danger_alert');
            $browser->assertRouteIs('login');
            $browser->assertSee('The email field is required.');
            $browser->assertSee('The password field is required.');
        });
    }

    /**
    * @test
    */
    public function it_registers_a_user()
    {
        $this->browse(function (Browser $browser) {
            $user = make(User::class);

            $browser->visit(route('register'));
            $browser->type('@register_first_name', $user->first_name);
            $browser->type('@register_last_name', $user->last_name);
            $browser->type('@register_email', $user->email);
            $browser->type('@register_password', 'secret');
            $browser->type('@register_password_confirmation', 'secret');
            $browser->press('@register_submit');
            $browser->waitForReload();
            $browser->assertRouteIs('sites.index');

            $this->assertDatabaseHas('users', [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
            ]);
        });
    }

    /**
    * @test
    */
    public function it_displays_registration_validation_errors()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('register'));
            $browser->press('@register_submit');
            $browser->waitFor('@danger_alert');
            $browser->assertSee('The first name field is required.');
            $browser->assertSee('The last name field is required.');
            $browser->assertSee('The email field is required.');
            $browser->assertSee('The password field is required.');
            $browser->assertRouteIs('register');
        });
    }

    /**
    * @test
    */
    public function it_allows_a_user_to_request_a_forgotten_password_reset()
    {
        $this->browse(function (Browser $browser) {
            $user = create(User::class);

            $browser->visit(route('password.request'));
            $browser->type('@reset_password_email', $user->email);
            $browser->press('@reset_password_submit');
            $browser->assertRouteIs('password.request');
            $browser->waitForText('We have e-mailed your password reset link!');
        });
    }

    /**
    * @test
    */
    public function it_displays_forgotten_password_reset_validation_errors()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('password.request'));
            $browser->press('@reset_password_submit');
            $browser->waitFor('@danger_alert');
            $browser->assertSee('The email field is required.');
            $browser->assertRouteIs('password.request');
        });
    }

    /**
    * @test
    */
    public function it_logs_out_a_user()
    {
        $this->browse(function (Browser $browser) {
            $user = $this->manualSignIn($browser);

            $browser->visit('/dashboard');
            $browser->pause(1000);
            $browser->click('@app_header_links');
            $browser->press('@logout_form_submit');
            $browser->waitForReload();
            $browser->assertRouteIs('login');
        });
    }
}
