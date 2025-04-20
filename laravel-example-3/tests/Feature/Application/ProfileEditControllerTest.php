<?php

namespace Tests\Feature\Application;

use App\User;
use Tests\TestCase;
use Tests\Support\TestUserData;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreStart
class ProfileEditControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_updates_the_user()
    {
        // Setup
        $testData = $this->getTestData($user = $this->signInWithEvents());
        $requestData = $this->getTestRequestData($testData);

        // Execute
        $response = $this->patch(route('profile.update'), $requestData->toArray());

        // Check response
        $response->assertStatus(302)
                 ->assertSessionHas('status')
                 ->assertSessionHas('success', true);

        // Check user
        $user = $user->fresh();
        $testData->except('desiredPurchaseCriteria', 'professionalInformation')->each(function ($value, $key) use ($user) {
            // Work phone has all formatting stripped.
            if ('work_phone' === $key) {
                $value = preg_replace('/[^0-9]/', '', $value);
            }

            $this->assertEquals($value, $user->$key);
        });

        // Check Desired Purchase Criteria
        $desiredPurchaseCriteria = $user->desiredPurchaseCriteria;
        $testData->get('desiredPurchaseCriteria')->each(function ($value, $key) use ($desiredPurchaseCriteria) {
            $this->assertEquals($value, $desiredPurchaseCriteria->$key);
        });

        // Check Professional Information
        $professionalInformation = $user->professionalInformation;
        $testData->get('professionalInformation')->each(function ($value, $key) use ($professionalInformation) {
            $this->assertEquals($value, $professionalInformation->$key);
        });
    }

    /**
     * @test
     */
    public function it_validates_the_request_has_required_fields()
    {
        // Setup
        $requestData = $this->getTestRequestData($this->getTestData($user = $this->signInWithEvents()));
        $requestData->put('email', null);
        $requestData->put('first_name', null);
        $requestData->put('last_name', null);

        // Execute
        $response = $this->patch(route('profile.update'), $requestData->toArray());

        // Check response
        $response->assertStatus(302)
                         ->assertSessionHasErrors(['first_name', 'last_name', 'email']);
    }

    /**
     * @test
     */
    public function it_validates_the_request_field_types()
    {
        // Setup
        $requestData = $this->getTestRequestData($this->getTestData($user = $this->signInWithEvents()));
        $requestData->put('email', 'notanemail');
        $tagline = substr($this->faker->paragraphs(50, true), 0, 401);
        $requestData->put('tagline', $tagline);

        // Execute
        $response = $this->patch(route('profile.update'), $requestData->toArray());

        // Check response
        $response->assertStatus(302)
                 ->assertSessionHasErrors(['email']);
    }

    /**
     * @test
     */
    public function it_redirects_with_a_success_message_after_successful_submission()
    {
        // Setup
        $requestData = $this->getTestRequestData($this->getTestData($user = $this->signInWithEvents()));

        // Execute
        $response = $this->patch(route('profile.update'), $requestData->toArray());

        // Assert
        $response->assertStatus(302)
                 ->assertSessionHas('status')
                 ->assertSessionHas('success', true);
    }

    /**
     * @test
     */
    public function it_uploads_the_photo_url()
    {
        // Sign in a new user to upload the file.
        $user = $this->signInWithEvents(factory(User::class)->create(['photo_id' => null]));

        // Make sure the photo URL is currently null
        $this->assertNull($user->photo_id);

        // Attempt to upload the file.
        $response = $this->patch(route('profile.update'), [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'photo_url_file' => UploadedFile::fake()->image('avatar.jpg'),
        ]);

        // Make sure the request went ok,
        $response->assertStatus(302)
                 ->assertSessionHas('status')
                 ->assertSessionHas('success', true);

        // Make sure something was saved to the photo URL.
        $this->assertNotNull($user->fresh()->photo_id);
    }

    /**
     * @test
     */
    public function it_deletes_the_photo_url()
    {
        // Make a user so we can add a media item to it.
        $user = factory(User::class)->create();

        // Add a media file to the users media gallery.
        $photo_id = $user->addMedia($this->addTestFile($user))->toMediaCollection('photo_url')->id;
        $user->photo_id = $photo_id;
        $user->save();
        $image = $user->photo->getPath();

        // Make sure the file actually exists.
        $this->assertFileExists($image);

        // Sign in a new user to upload the file.
        $user = $this->signInWithEvents($user);

        // Make sure the photo URL is not currently null
        $this->assertNotNull($user->photo_id);

        // Attempt to upload the file.
        $response = $this->patch(route('profile.update'), [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'photo_url_delete' => '1',
        ]);

        // Make sure the request went ok,
        $response->assertStatus(302)
                 ->assertSessionHas('status')
                 ->assertSessionHas('success', true);

        // Make sure the photo URL was removed.
        $user = $user->fresh();
        $this->assertNull($user->photo_id);
        $this->assertNotEquals($photo_id, $user->photo_id);
        $this->assertFileNotExists($image);
    }

    /**
     * @test
     */
    public function it_replaces_the_photo_url()
    {
        // Sign in a new user to upload the file.
        $user = $this->signInWithEvents(factory(User::class)->create());

        // Add a media file to the users media gallery.
        $image = $this->addTestFile($user);
        $photo_id = $user->addMedia($image)->toMediaCollection('photo_url')->id;
        $user->photo_id = $photo_id;
        $user->save();

        // Make sure the photo URL is currently null
        $this->assertNotNull($currentUrl = $user->photo_url);

        // Attempt to upload the file.
        $response = $this->patch(route('profile.update'), [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'photo_url_delete' => '1',
            'photo_url_file' => UploadedFile::fake()->image('avatar.jpg'),
        ]);

        // Make sure the request went ok,
        $response->assertStatus(302)
                 ->assertSessionHas('status')
                 ->assertSessionHas('success', true);

        // Make sure something was saved to the photo URL and it was not the previous URL.
        $user = $user->fresh();
        $this->assertNotNull($user->photo_url);
        $this->assertNotEquals($currentUrl, $user->photo_url);
    }

    /**
     * @test
     */
    public function it_uploads_the_company_logo_file()
    {
        // Sign in a new user to upload the file.
        $user = $this->signInWithEvents(factory(User::class)->create());

        // Make sure the professional information company logo id is not set.
        $user->professionalInformation->company_logo_id = null;
        $user->professionalInformation->save();

        // Make sure the photo URL is currently null
        $this->assertNull($user->professionalInformation->fresh()->company_logo_id);

        // Attempt to upload the file.
        $response = $this->patch(route('profile.update'), [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'company_logo_url_file' => UploadedFile::fake()->image('avatar.jpg'),
        ]);

        // Make sure the request went ok,
        $response->assertStatus(302)
                 ->assertSessionHas('status')
                 ->assertSessionHas('success', true);

        // Make sure something was saved to the photo URL.
        $this->assertNotNull($user->professionalInformation->fresh()->company_logo_id);
    }

    /**
     * @test
     */
    public function it_deletes_the_company_logo_file()
    {
        $this->withoutExceptionHandling();

        // Make a user so we can add a logo item to it.
        $user = factory(User::class)->create();

        // Add a logo file to the users logo gallery.
        $logo_id = $user->professionalInformation
                          ->addMedia($this->addTestFile($user))
                          ->toMediaCollection('company_logo')->id;
        $user->professionalInformation->company_logo_id = $logo_id;
        $user->save();
        $image = $user->professionalInformation->company_logo->getPath();

        // Make sure the file actually exists.
        $this->assertFileExists($image);

        // Sign in a new user to upload the file.
        $user = $this->signInWithEvents($user);

        // Make sure the photo URL is not currently null
        $this->assertNotNull($user->professionalInformation->company_logo_id);

        // Attempt to upload the file.
        $response = $this->patch(route('profile.update'), [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'company_logo_url_delete' => '1',
        ]);

        // Make sure the request went ok,
        $response->assertStatus(302)
                 ->assertSessionHas('status')
                 ->assertSessionHas('success', true);

        // Make sure the photo URL was removed.
        $user = $user->fresh();
        $this->assertNull($user->professionalInformation->company_logo_id);
        $this->assertNotEquals($logo_id, $user->professionalInformation->company_logo_id);
        $this->assertFileNotExists($image);
    }

    /**
     * @test
     */
    public function it_replaces_the_company_logo_file()
    {
        // Sign in a new user to upload the file.
        $user = $this->signInWithEvents(factory(User::class)->create());

        // Add a media file to the users media gallery.
        $image = $this->addTestFile($user);
        $company_logo_id = $user->professionalInformation
                                           ->addMedia($image)
                                           ->toMediaCollection('company_logo')->id;
        $user->professionalInformation->company_logo_id = $company_logo_id;
        $user->professionalInformation->save();

        // Make sure the photo URL is currently null
        $this->assertNotNull($currentUrl = $user->professionalInformation->company_logo_url);

        // Attempt to upload the file.
        $response = $this->patch(route('profile.update'), [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'company_logo_url_delete' => '1',
            'company_logo_url_file' => UploadedFile::fake()->image('avatar.jpg'),
        ]);

        // Make sure the request went ok,
        $response->assertStatus(302)
                 ->assertSessionHas('status')
                 ->assertSessionHas('success', true);

        // Make sure something was saved to the company logo URL and it was not the previous URL.
        $user = $user->fresh();
        $this->assertNotNull($user->professionalInformation->company_logo_url);
        $this->assertNotEquals($currentUrl, $user->professionalInformation->company_logo_url);
    }

    /**
     * Gets the request data.
     *
     * @param  Illuminate\Support\Collection $testData
     *
     * @return Illuminate\Support\Collection
     */
    protected function getTestRequestData($testData)
    {
        return (new TestUserData)->getRequest($testData);
    }

    /**
     * Get the test data.
     *
     * @param  App\User $user
     *
     * @return Illuminate\Support\Collection
     */
    protected function getTestData($user)
    {
        return (new TestUserData)->get($user);
    }

    /**
     * Adds a test file to the users directory.
     *
     * @param  User $user
     * @param  string $extension
     *
     * @return string
     */
    protected function addTestFile($user, $extension = 'jpg')
    {
        // Make the users directory if needed.
        $storagePath = storage_path();
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        // Move the mock file into the users directory.
        $filename = "test.{$extension}";
        $newPath =  "{$storagePath}/{$filename}";
        copy(base_path("tests/mocks/files/{$filename}"), $newPath);

        return $newPath;
    }
}
