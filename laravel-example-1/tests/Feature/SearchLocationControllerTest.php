<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\SearchLocation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchLocationControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_requires_a_user_to_be_authenticated_to_create_a_search_location()
    {
        $this->assertEmpty(SearchLocation::all());
        $this->post(route('search-locations.store'), [
            'name' => 'Test Name',
            'address' => '5711 Huntington Valley Ct, St. Louis, MO 63129',
        ])
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->assertEmpty(SearchLocation::all());
    }

    /** @test */
    public function it_only_allows_owners_to_delete_a_search_location()
    {
        $searchLocation = SearchLocation::factory()->create(['user_id' => User::factory()]);
        $this->loginUser();

        $this->delete(route('search-locations.destroy', ['search_location' => $searchLocation]))
            ->assertStatus(404);

        $this->assertNotNull($searchLocation->fresh());
    }

    /** @test */
    public function it_creates_a_search_location()
    {
        $this->withoutExceptionHandling();
        $user = $this->loginUser();
        $this->post(route('search-locations.store'), [
            'name' => $name = 'Test Name',
            'address' => $address = '5711 Huntington Valley Ct, St. Louis, MO 63129',
        ])
            ->assertSuccessful()
            ->assertJsonStructure([
                'success_message',
                'location',
            ]);

        $searchLocation = SearchLocation::first();
        $this->assertNotNull($searchLocation);
        $this->assertEquals($address, $searchLocation->address);
        $this->assertEquals($name, $searchLocation->name);
        $this->assertEquals($user->id, $searchLocation->user_id);
    }

    /** @test */
    public function it_defaults_name_to_address_when_creating_a_search_location()
    {
        $user = $this->loginUser();
        $this->post(route('search-locations.store'), [
            // 'name' => $name = 'Test Name',
            'address' => $address = '5711 Huntington Valley Ct, St. Louis, MO 63129',
        ])
            ->assertSuccessful()
            ->assertJson([
                'success_message' => 'Search location saved successfully.'
            ]);

        $searchLocation = SearchLocation::first();
        $this->assertNotNull($searchLocation);
        $this->assertEquals($address, $searchLocation->address);
        $this->assertEquals($address, $searchLocation->name);
        $this->assertEquals($user->id, $searchLocation->user_id);
    }

    /** @test */
    public function it_requires_address_to_create_a_search_location()
    {
        $this->loginUser();
        $this->json('post', route('search-locations.store'), [
            'name' => 'Test Name',
            // 'address' => '5711 Huntington Valley Ct, St. Louis, MO 63129',
        ])
            ->assertJsonValidationErrors('address');
    }

    /** @test */
    public function it_requires_address_to_be_a_string_to_create_a_search_location()
    {
        $this->loginUser();
        $this->json('post', route('search-locations.store'), [
            'name' => 'Test Name',
            'address' => [
                'latitude' => '-90.3483247',
                'longitude' => '38.4705537',
            ],
        ])
            ->assertJsonValidationErrors('address');
    }

    /** @test */
    public function it_requires_name_to_be_a_string_to_create_a_search_location()
    {
        $this->loginUser();
        $this->json('post', route('search-locations.store'), [
            'name' => [
                'location_name' => 'Test Name',
            ],
            'address' => '5711 Huntington Valley Ct, St. Louis, MO 63129',
        ])
            ->assertJsonValidationErrors('name');
    }

    /** @test */
    public function it_deletes_a_search_location()
    {
        $searchLocation = SearchLocation::factory()->create([
            'user_id' => $this->loginUser(),
        ]);
        $this->delete(route('search-locations.destroy', ['search_location' => $searchLocation]))
            ->assertSuccessful()
            ->assertJson([
                'success_message' => 'Search location removed successfully.'
            ]);

        $this->assertNull($searchLocation->fresh());
    }
}
