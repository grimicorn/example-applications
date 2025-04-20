<?php

namespace Tests\Browser\Application;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Tests\Support\TestUserData;
use Illuminate\Support\Collection;
use Tests\Support\TestApplicationData;
use Tests\Browser\Pages\Application\ProfileEdit;
use Illuminate\Foundation\Testing\DatabaseMigrations;

// @codingStandardsIgnoreStart
class ProfileEditTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $successMessage =  'Your profile has been successfully updated!';

    public function setUp()
    {
        parent::setUp();

        $this->seed(\BusinessCategoriesSeeder::class);
    }

    /**
     * @test
     */
    public function it_allows_the_users_to_fill_out_basic_information()
    {
        $this->browse(function (Browser $browser) {
            // Setup
            $user = factory(User::class)->create(['primary_roles' => []]);
            $data = $this->getTestData($user);
            $request = $this->getTestRequestData($data);
            $expectedRoles = $data['primary_roles'];
            sort($expectedRoles);

            // Visit the page.
            $this->visit($user, $browser);

            // Check the primary roles.
            foreach ($request['primary_roles'] as $key => $value) {
                $browser->click("label[for=\"primary_roles[{$key}]\"]");
            }

            // Toggle list in professionals directory.
            $data['listed_in_directory'] = !$user->listed_in_directory;
            $browser->click('.fe-input-listed_in_directory-wrap .fe-input-toggle-display');

            // Try uploading a file.
            $browser->attach('photo_url_file', $this->faker->image());

            // Submit the form and make sure it submits successfully
            $this->submit($browser);

            // Assert that the user data was saved.
            $user = $user->fresh();
            $actualRoles = $user->primary_roles;
            sort($actualRoles);
            $this->assertEquals($expectedRoles, $actualRoles);
            $this->assertEquals($data['listed_in_directory'], $user->listed_in_directory);
            $this->assertNotNull($user->photo_url);
        });
    }

    /**
     * @test
     */
    public function it_successfully_deletes_the_photo_url()
    {
        // The alert can not be clicked if Chrome is headless
        $this->disableHeadlessChrome();

        $this->browse(function (Browser $browser) {
            // Make a user so we can add a media item to it.
            $user = factory(User::class)->create();

            // Add a temporary media file to the users media gallery.
            $storagePath = storage_path('/');
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0755, true);
            }
            $image = $this->faker->image($storagePath);
            $photo_id = $user->addMedia($image)->toMediaCollection('photo_url')->id;
            $user->photo_id = $photo_id;
            $user->save();

            // Make sure the file actually exists.
            $this->assertFileExists($user->getMedia('company_logo')->first()->getPath());

            // Get test data
            $data = $this->getTestData($user);
            $request = $this->getTestRequestData($data);

            // Make sure the user currently has an image
             $this->assertNotNull($user->photo_url);

            // Visit the page.
            $this->visit($user, $browser);

            // Click on the delete button to remove the file
            $browser->click('.fe-input-photo_url-wrap .single-image-delete-btn');

            // Accept the confirmation.
            $browser->driver->switchTo()->alert()->accept();

            // Submit the form and make sure it submits successfully
            $this->submit($browser);

            // Assert that the user data was saved.
            $this->assertNull($user->fresh()->photo_url);
        });

        // Reset Chrome to run in headless mode.
        $this->enableHeadlessChrome();
    }

    /**
     * @test
     */
    public function it_allows_the_users_to_fill_out_contact_information()
    {
        $this->browse(function (Browser $browser) {
            // Setup
            $user = factory(User::class)->create();
            $data = $this->getTestData($user);
            $request = $this->getTestRequestData($data);

            // Visit the page.
            $this->visit($user, $browser);

            // Update the user's contact information
            $browser->type('work_phone', $request['work_phone']);

            // Submit the form
            $this->submit($browser);

            // Assert that the user's contact information was successfully updated.
            $user = $user->fresh();
            $this->assertEquals($request['work_phone'], $user->work_phone);
        });
    }

    /**
     * @test
     */
    public function it_allows_the_users_to_fill_out_personal_information()
    {
        $this->browse(function (Browser $browser) {
            // Setup
            $user = factory(User::class)->create();
            $data = $this->getTestData($user);
            $request = $this->getTestRequestData($data);
            $request['bio'] = $this->faker->paragraph;

            // Visit the page.
            $this->visit($user, $browser);

            // Update the user's personal information
            $browser->type('tagline', $request['tagline']);
            $browser->type('bio', $request['bio']);

            // Submit the form
            $this->submit($browser);

            // Assert that the user's personal information was successfully updated.
            $user = $user->fresh();
            $this->assertEquals($request['tagline'], $user->tagline);
            $this->assertEquals($request['bio'], $user->bio);
        });
    }

    /**
     * @test
     * @todo Test business categories select
     * @todo Test locations
     */
    public function it_allows_the_users_to_fill_out_desired_purchase_criteria()
    {
        $this->browse(function (Browser $browser) {
            // Setup
            $user = factory(User::class)->create();
            $data = $this->getTestData($user);
            $request = collect($this->getTestRequestData($data)['desiredPurchaseCriteria']);

            // Visit the page.
            $this->visit($user, $browser);

            // Update the user's desired purchase criteria
            $browser->type('desiredPurchaseCriteria[asking_price_minimum]', $request['asking_price_minimum']);
            $browser->type('desiredPurchaseCriteria[asking_price_maximum]', $request['asking_price_maximum']);
            $browser->type('desiredPurchaseCriteria[revenue_minimum]', $request['revenue_minimum']);
            $browser->type('desiredPurchaseCriteria[revenue_maximum]', $request['revenue_maximum']);
            $browser->type('desiredPurchaseCriteria[ebitda_minimum]', $request['ebitda_minimum']);
            $browser->type('desiredPurchaseCriteria[ebitda_maximum]', $request['ebitda_maximum']);
            $browser->type('desiredPurchaseCriteria[pre_tax_income_minimum]', $request['pre_tax_income_minimum']);
            $browser->type('desiredPurchaseCriteria[pre_tax_income_maximum]', $request['pre_tax_income_maximum']);
            $browser->type('desiredPurchaseCriteria[discretionary_cash_flow_minimum]', $request['discretionary_cash_flow_minimum']);
            $browser->type('desiredPurchaseCriteria[discretionary_cash_flow_maximum]', $request['discretionary_cash_flow_maximum']);

            // Submit the form
            $this->submit($browser);

            // Assert that the user's desired purchase criteria was successfully updated.
            $criteria = $user->fresh()->desiredPurchaseCriteria->fresh();
            $this->assertEquals($request['asking_price_minimum'], $criteria->asking_price_minimum);
            $this->assertEquals($request['asking_price_maximum'], $criteria->asking_price_maximum);
            $this->assertEquals($request['revenue_minimum'], $criteria->revenue_minimum);
            $this->assertEquals($request['revenue_maximum'], $criteria->revenue_maximum);
            $this->assertEquals($request['ebitda_minimum'], $criteria->ebitda_minimum);
            $this->assertEquals($request['ebitda_maximum'], $criteria->ebitda_maximum);
            $this->assertEquals($request['pre_tax_income_minimum'], $criteria->pre_tax_income_minimum);
            $this->assertEquals($request['pre_tax_income_maximum'], $criteria->pre_tax_income_maximum);
            $this->assertEquals($request['discretionary_cash_flow_minimum'], $criteria->discretionary_cash_flow_minimum);
            $this->assertEquals($request['discretionary_cash_flow_maximum'], $criteria->discretionary_cash_flow_maximum);
        });
    }

    /**
     * @test
     * @todo Website links
     * @todo Areas Served
     * @todo Business Broker Designations
     * @todo Occupation - Other
     * @group failing
     */
    public function it_allows_the_users_to_fill_out_professional_information()
    {
        $this->browse(function (Browser $browser) {
            // Setup
            $appData = new TestApplicationData;
            $user = factory(User::class)->create();
            $data = $this->getTestData($user);
            $request = collect($this->getTestRequestData($data)['professionalInformation']);
            $request->put('professional_background', $this->faker->paragraph);
            $request->put('occupation', $appData->getTestOccupation($addOther = false));

            // Visit the page.
            $this->visit($user, $browser);

            // Update the user's desired basic professional information
            $browser->select('professionalInformation[occupation]', $request->get('occupation'));
            $browser->type('professionalInformation[years_of_experience]', $request->get('years_of_experience'));
            $browser->type('professionalInformation[company_name]', $request->get('company_name'));
            $browser->type('professionalInformation[professional_background]', $request->get('professional_background'));

            // Submit the form
            $this->submit($browser);

            // Assert that the user's desired purchase criteria was successfully updated.
            $information = $user->fresh()->professionalInformation->fresh();
            $this->assertEquals($request['occupation'], $information->occupation);
            $this->assertEquals($request['years_of_experience'], $information->years_of_experience);
            $this->assertEquals($request['company_name'], $information->company_name);
            $this->assertEquals($request['professional_background'], $information->professional_background);
        });
    }

    protected function getTestRequestData(Collection $testData)
    {
        return (new TestUserData)->getRequest($testData);
    }

    protected function getTestData(User $user)
    {
        return (new TestUserData)->get($user);
    }

    protected function visit(User $user, Browser $browser)
    {
        $browser->loginAs($user)->visit(route('profile.edit'));
    }

    protected function submit(Browser $browser)
    {
        $browser->click('button.btn-model-submit')
                           ->waitFor('.alert-success')
                           ->assertSeeIn('.alert-success', $this->successMessage);
    }
}
