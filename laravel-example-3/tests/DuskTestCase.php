<?php

namespace Tests;

use Laravel\Dusk\TestCase as BaseTestCase;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $faker;
    protected $driverArguments = [
        '--disable-gpu',
        '--headless'
    ];

    public function setUp()
    {
        parent::setUp();

        // Add the business categories since many tests depend on these.
        $this->seed(\BusinessCategoriesSeeder::class);

        $this->disableScout();
        $this->faker = \Faker\Factory::create();
    }

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
        $options = (new ChromeOptions)
                          ->addArguments($this->driverArguments);

        return RemoteWebDriver::create(
            'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY,
                $options
            )
        );
    }

    /**
     * Disables headless Chrome
     *
     * @return [type] [description]
     */
    protected function disableHeadlessChrome()
    {
        $this->driverArguments = [
            '--disable-gpu',
        ];
    }

    protected function enableHeadlessChrome()
    {
        $this->driverArguments = [
            '--disable-gpu',
            '--headless'
        ];
    }

    /**
     * Disables scout drive.
     */
    public function disableScout()
    {
        config(['scout.driver' => 'null']);
    }
}
