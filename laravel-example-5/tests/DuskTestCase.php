<?php

namespace Tests;

use App\User;
use Laravel\Dusk\Browser;
use Laravel\Dusk\TestCase as BaseTestCase;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
        static::startChromeDriver();
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        $options = (new ChromeOptions)->addArguments([
            '--disable-gpu',
            '--headless'
        ]);

        return RemoteWebDriver::create(
            'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY,
                $options
            )
        );
    }

    protected function manualSignIn(Browser $browser)
    {
        if (auth()->check()) {
            auth()->logout();
        }

        $user = create(User::class, [
            'password' => bcrypt($password = uniqid()),
        ]);

        $browser->visit('/login');
        $browser->type('@login_email', $user->email);
        $browser->type('@login_password', $password);
        $browser->press('@login_submit');
        $browser->waitForReload();

        return $user;
    }

    public function tearDown()
    {
        foreach (static::$browsers as $browser) {
            $browser->logout();
        }

        parent::tearDown();
    }
}
