<?php

// phpcs:ignoreFile

namespace Tests\Feature;

use App\Models\Tag;
use Tests\TestCase;
use App\Models\Link;
use App\Models\Location;
use App\Models\LocationIcon;
use App\Enums\LocationTrafficLevelEnum;
use App\Enums\LocationAccessDifficultyEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LocationControllerCreateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_requires_a_user_to_be_authenticated_to_create_a_location()
    {
        $this->markTestIncomplete('Not implemented');
    }

    /** @test */
    public function it_requires_a_user_to_be_authenticated_to_view_the_location_create_page()
    {
        $this->markTestIncomplete('Not implemented');
    }

    /** @test */
    public function it_creates_a_location()
    {
        $user = $this->loginUser();

        $this->assertEmpty(Location::all());

        $tags = Tag::factory()->count(3)->create()->pluck('id');
        $links = Link::factory()->count(3)->make()->map->only(['url', 'name']);
        $icon = LocationIcon::factory()->create();

        $this->post(route('locations.store'), [
            'name' => $name = 'Blue Spring',
            'address' => $address = 'County Road 535, Ellington, Missouri 63638, US',
            'best_time_of_day_to_visit' => $bestTimeOfDayToVisit = 'Morning',
            'best_time_of_year_to_visit' => $bestTimeOfYearToVisit = 'Summer',
            'rating' => $rating = 3,
            'visited' => 1,
            'tags' => $tags->toArray(),
            'links' => $links->toArray(),
            'notes' => 'Some notes for a location...',
            'icon_id' => $icon->id,
            'access_difficulty' => $accessDifficulty = $this->faker->randomEnum(LocationAccessDifficultyEnum::class)->value,
            'traffic_level' => $trafficLevel = $this->faker->randomEnum(LocationTrafficLevelEnum::class)->value,
            'walk_distance' => $walkDistance = 4.25,
        ])
            ->assertRedirect(route('locations.index'))
            ->assertSessionHas('success_message');

        $location = Location::first();

        // Location Attributes
        $this->assertNotNull($location);
        $this->assertEquals($name, $location->name);
        $this->assertEquals($address, $location->address);
        $this->assertEquals($bestTimeOfDayToVisit, $location->best_time_of_day_to_visit);
        $this->assertEquals($bestTimeOfYearToVisit, $location->best_time_of_year_to_visit);
        $this->assertEquals($rating, $location->rating);
        $this->assertTrue($location->visited);
        $this->assertEquals(37.1824236, $location->latitude);
        $this->assertEquals(-91.161104, $location->longitude);
        $urlEncodedAddress = urlencode($address);
        $this->assertEquals(
            'https://www.google.com/maps/dir//County+Road+535%2C+Ellington%2C+Missouri+63638%2C+US/@37.1824236,-91.1611036,13z/',
            $location->google_maps_link
        );
        $this->assertEquals($user->id, $location->user_id);
        $this->assertEquals('County Road 535', $location->route);
        $this->assertEquals('Ellington', $location->locality);
        $this->assertEquals('MO', $location->administrative_area_level_1_abbreviation);
        $this->assertEquals('Missouri', $location->administrative_area_level_1);
        $this->assertEquals('US', $location->country);
        $this->assertEquals('63638', $location->postal_code);
        $this->assertEquals('America/Chicago', $location->timezone);
        $this->assertEquals('Some notes for a location...', $location->notes);
        $this->assertEquals($icon->id, $location->icon_id);
        $this->assertEquals($accessDifficulty, $location->access_difficulty);
        $this->assertEquals($trafficLevel, $location->traffic_level);
        $this->assertEquals($walkDistance, $location->walk_distance);

        // Tags
        $this->assertEquals(
            $tags->sort()->values()->toArray(),
            $location->tags->pluck('id')->sort()->values()->toArray()
        );

        // Links
        $this->assertEquals(
            $links->pluck('url')->sort()->values()->toArray(),
            $location->links->pluck('url')->sort()->values()->toArray()
        );
    }

    /** @test */
    public function it_creates_a_location_and_redirects_to_create_if_add_new_exists()
    {
        $this->loginUser();

        $this->assertEmpty(Location::all());

        $this->post(route('locations.store'), [
            'name' => $name = 'Blue Spring',
            'address' => $address = 'County Road 535, Ellington, Missouri 63638, US',
            'add_new' => 1,
        ])
            ->assertRedirect(route('locations.create'))
            ->assertSessionHas('success_message');

        $location = Location::first();

        // Location Attributes
        $this->assertNotNull($location);
        $this->assertEquals($name, $location->name);
        $this->assertEquals($address, $location->address);
    }

    /** @test */
    public function it_requires_name_to_create_a_location()
    {
        $this->loginUser();

        $this->assertEmpty(Location::all());

        $this->post(route('locations.store'), [
            // 'name' => 'Blue Spring',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'best_time_of_day_to_visit' => 'Morning',
            'best_time_of_year_to_visit' => 'Summer',
            'rating' => 3,
            'visited' => 1,
            'tags' => Tag::factory()->count(3)->create()->pluck('id'),
        ])
            ->assertSessionHasErrors('name');

        $this->assertEmpty(Location::all());
    }

    /** @test */
    public function it_requires_name_to_be_a_string_to_create_a_location()
    {
        $this->loginUser();

        $this->assertEmpty(Location::all());

        $this->post(route('locations.store'), [
            'name' => ["This shouldn't work..."],
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'best_time_of_day_to_visit' => 'Morning',
            'best_time_of_year_to_visit' => 'Summer',
            'rating' => 3,
            'visited' => 1,
            'tags' => Tag::factory()->count(3)->create()->pluck('id'),
        ])
            ->assertSessionHasErrors('name');

        $this->assertEmpty(Location::all());
    }

    /** @test */
    public function it_requires_address_to_create_a_location()
    {
        $this->loginUser();

        $this->assertEmpty(Location::all());

        $this->post(route('locations.store'), [
            'name' => 'Blue Spring',
            // 'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'best_time_of_day_to_visit' => 'Morning',
            'best_time_of_year_to_visit' => 'Summer',
            'rating' => 3,
            'visited' => 1,
            'tags' => Tag::factory()->count(3)->create()->pluck('id'),
        ])
            ->assertSessionHasErrors('address');

        $this->assertEmpty(Location::all());
    }

    /** @test */
    public function it_requires_address_to_be_a_string_to_create_a_location()
    {
        $this->loginUser();

        $this->assertEmpty(Location::all());

        $this->post(route('locations.store'), [
            'name' => 'Blue Spring',
            'address' => ["This shouldn't work..."],
            'best_time_of_day_to_visit' => 'Morning',
            'best_time_of_year_to_visit' => 'Summer',
            'rating' => 3,
            'visited' => 1,
            'tags' => Tag::factory()->count(3)->create()->pluck('id'),
        ])
            ->assertSessionHasErrors('address');

        $this->assertEmpty(Location::all());
    }

    /** @test */
    public function it_requires_best_time_of_day_to_visit_to_be_valid_to_create_a_location()
    {
        // Should be within resolve(BestVisitTimes::class)->ofDay()
        $this->loginUser();

        $this->assertEmpty(Location::all());

        $this->post(route('locations.store'), [
            'name' => 'Blue Spring',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'best_time_of_day_to_visit' => 'Not a Valid Time of Day',
            'best_time_of_year_to_visit' => 'Summer',
            'rating' => 3,
            'visited' => 1,
            'tags' => Tag::factory()->count(3)->create()->pluck('id'),
        ])
            ->assertSessionHasErrors('best_time_of_day_to_visit');

        $this->assertEmpty(Location::all());
    }

    /** @test */
    public function it_requires_best_time_of_year_to_visit_to_be_valid_to_create_a_location()
    {
        $this->loginUser();

        $this->assertEmpty(Location::all());

        $this->post(route('locations.store'), [
            'name' => 'Blue Spring',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'best_time_of_day_to_visit' => 'Morning',
            'best_time_of_year_to_visit' => 'Not a Valid Time of Year',
            'rating' => 3,
            'visited' => 1,
            'tags' => Tag::factory()->count(3)->create()->pluck('id'),
        ])
            ->assertSessionHasErrors('best_time_of_year_to_visit');

        $this->assertEmpty(Location::all());
    }

    /** @test */
    public function it_requires_rating_to_be_a_number_to_create_a_location()
    {
        $this->loginUser();

        $this->assertEmpty(Location::all());

        $this->post(route('locations.store'), [
            'name' => 'Blue Spring',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'best_time_of_day_to_visit' => 'Morning',
            'best_time_of_year_to_visit' => 'Summer',
            'rating' => 'three',
            'visited' => 1,
            'tags' => Tag::factory()->count(3)->create()->pluck('id'),
        ])
            ->assertSessionHasErrors('rating');

        $this->assertEmpty(Location::all());
    }

    /** @test */
    public function it_requires_rating_to_be_greater_than_or_equal_to_1_to_create_a_location()
    {
        $this->loginUser();

        $this->assertEmpty(Location::all());

        $this->post(route('locations.store'), [
            'name' => 'Blue Spring',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'best_time_of_day_to_visit' => 'Morning',
            'best_time_of_year_to_visit' => 'Summer',
            'rating' => 0,
            'visited' => 1,
            'tags' => Tag::factory()->count(3)->create()->pluck('id'),
        ])
            ->assertSessionHasErrors('rating');

        $this->assertEmpty(Location::all());
    }

    /** @test */
    public function it_requires_rating_to_be_less_than_or_equal_to_5_to_create_a_location()
    {
        $this->loginUser();

        $this->assertEmpty(Location::all());

        $this->post(route('locations.store'), [
            'name' => 'Blue Spring',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'best_time_of_day_to_visit' => 'Morning',
            'best_time_of_year_to_visit' => 'Summer',
            'rating' => 6,
            'visited' => 1,
            'tags' => Tag::factory()->count(3)->create()->pluck('id'),
        ])
            ->assertSessionHasErrors('rating');

        $this->assertEmpty(Location::all());
    }

    /** @test */
    public function it_requires_visited_to_be_a_boolean_to_create_a_location()
    {
        $this->loginUser();

        $this->assertEmpty(Location::all());

        $this->post(route('locations.store'), [
            'name' => 'Blue Spring',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'best_time_of_day_to_visit' => 'Morning',
            'best_time_of_year_to_visit' => 'Summer',
            'rating' => 3,
            'visited' => 'yes',
            'tags' => Tag::factory()->count(3)->create()->pluck('id'),
        ])
            ->assertSessionHasErrors('visited');

        $this->assertEmpty(Location::all());
    }

    /** @test */
    public function it_requires_tags_to_be_an_array_to_create_a_location()
    {
        $this->loginUser();

        $this->assertEmpty(Location::all());

        $this->post(route('locations.store'), [
            'name' => 'Blue Spring',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'best_time_of_day_to_visit' => 'Morning',
            'best_time_of_year_to_visit' => 'Summer',
            'rating' => 3,
            'visited' => 1,
            'tags' => 'Tag 1,Tag 2,Tag 3',
        ])
            ->assertSessionHasErrors('tags');

        $this->assertEmpty(Location::all());
    }

    /** @test */
    public function it_requires_links_to_be_an_array_to_create_a_location()
    {
        $this->loginUser();

        $this->assertEmpty(Location::all());

        $this->post(route('locations.store'), [
            'name' => 'Blue Spring',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'best_time_of_day_to_visit' => 'Morning',
            'best_time_of_year_to_visit' => 'Summer',
            'rating' => 3,
            'visited' => 1,
            'links' => 'Link 1,Link 2,Link 3',
        ])
            ->assertSessionHasErrors('links');

        $this->assertEmpty(Location::all());
    }

    /** @test */
    public function it_requires_link_urls_if_id_is_not_present_to_create_a_location()
    {
        $this->loginUser();

        $this->assertEmpty(Location::all());

        $sharedAttributes = [
            'name' => 'Blue Spring',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'best_time_of_day_to_visit' => 'Morning',
            'best_time_of_year_to_visit' => 'Summer',
            'rating' => 3,
            'visited' => 1,
        ];

        // Verify there is an error without link and id.
        $this->post(route('locations.store'), array_merge($sharedAttributes, [
            'links' => [
                ['name' => 'Link 1'],
                ['name' => 'Link 2'],
                ['name' => 'Link 3'],
            ],
        ]))
            ->assertSessionHasErrors('links.*.url');

        $this->assertEmpty(Location::all());

        // Verify it works with url.
        $links = Link::factory(3)->create();
        $this->post(route('locations.store'), array_merge($sharedAttributes, [
            'links' => $links->map->only(['url'])->toArray(),
        ]))
            ->assertSessionDoesntHaveErrors();

        $this->assertCount(1, Location::all());
    }

    /** @test */
    public function it_requires_link_ids_if_url_is_not_present_to_create_a_location()
    {
        $this->loginUser();

        $this->assertEmpty(Location::all());

        $sharedAttributes = [
            'name' => 'Blue Spring',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'best_time_of_day_to_visit' => 'Morning',
            'best_time_of_year_to_visit' => 'Summer',
            'rating' => 3,
            'visited' => 1,
        ];

        // Verify there is an error without link and id.
        $this->post(route('locations.store'), array_merge($sharedAttributes, [
            'links' => [
                ['name' => 'Link 1'],
                ['name' => 'Link 2'],
                ['name' => 'Link 3'],
            ],
        ]))
            ->assertSessionHasErrors('links.*.id');

        $this->assertEmpty(Location::all());

        // Verify it works with id.
        $links = Link::factory(3)->create();
        $this->post(route('locations.store'), array_merge($sharedAttributes, [
            'links' => $links->map->only(['id'])->toArray(),
        ]))
            ->assertSessionDoesntHaveErrors();

        $this->assertCount(1, Location::all());
    }

    /** @test */
    public function it_requires_notes_to_be_a_string_to_create_a_location()
    {
        $this->loginUser();

        $this->assertEmpty(Location::all());

        $this->post(route('locations.store'), [
            'name' => 'Blue Spring',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'best_time_of_day_to_visit' => 'Morning',
            'best_time_of_year_to_visit' => 'Summer',
            'rating' => 3,
            'visited' => 1,
            'tags' => Tag::factory()->count(3)->create()->pluck('id'),
            'notes' => ["This shouldn't work..."],
        ])
            ->assertSessionHasErrors('notes');

        $this->assertEmpty(Location::all());
    }

    /** @test */
    public function it_requires_icon_to_be_a_number_to_create_a_location()
    {
        $this->loginUser();

        $this->assertEmpty(Location::all());

        $this->post(route('locations.store'), [
            'name' => 'Blue Spring',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'best_time_of_day_to_visit' => 'Morning',
            'best_time_of_year_to_visit' => 'Summer',
            'rating' => 3,
            'visited' => 1,
            'tags' => Tag::factory()->count(3)->create()->pluck('id'),
            'icon_id' => 'one',
        ])
            ->assertSessionHasErrors('icon_id');

        $this->assertEmpty(Location::all());
    }

    /** @test */
    public function it_requires_icon_to_be_a_valid_icon_to_create_a_location()
    {
        $this->loginUser();

        $this->assertEmpty(Location::all());

        $this->post(route('locations.store'), [
            'name' => 'Blue Spring',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'best_time_of_day_to_visit' => 'Morning',
            'best_time_of_year_to_visit' => 'Summer',
            'rating' => 3,
            'visited' => 1,
            'tags' => Tag::factory()->count(3)->create()->pluck('id'),
            'icon_id' => 2000, // This will probably never be a valid icon_id but we can raise it if needed...
        ])
            ->assertSessionHasErrors('icon_id');

        $this->assertEmpty(Location::all());
    }

    /** @test */
    public function it_requires_a_user_to_be_authenticated_to_view_the_location_edit_page()
    {
        $this->markTestIncomplete('Not implemented');
    }

    /** @test */
    public function it_only_allows_users_to_be_to_view_the_location_edit_page_for_their_locations()
    {
        $this->markTestIncomplete('Not implemented');
    }

    /** @test */
    public function it_does_not_require_name_to_be_passed_create_a_location()
    {
        $this->markTestIncomplete();

        // $oldLocation = Location::factory()->oldRedingsMillBridge()->create([
        //     'user_id' => $this->loginUser()->id,
        // ]);

        // $this->assertCount(1, Location::all());

        // $this->from(route('locations.update', ['location' => $oldLocation]))
        //     ->patch(route('locations.update', ['location' => $oldLocation]), [
        //         // 'name' => $name = 'Blue Spring',
        //         'address' => $address = 'County Road 535, Ellington, Missouri 63638, US',
        //         'best_time_of_day_to_visit' => $bestTimeOfDayToVisit = 'Morning',
        //         'best_time_of_year_to_visit' => $bestTimeOfYearToVisit = 'Summer',
        //         'rating' => $rating = 3,
        //         'visited' => 1,
        //     ])
        //     ->assertSessionDoesntHaveErrors('name')
        //     ->assertRedirect(route('locations.update', ['location' => $oldLocation]))
        //     ->assertSessionHas('success_message');
    }

    /** @test */
    public function it_requires_name_to_not_be_null_if_it_is_present_to_create_a_location()
    {
        $this->markTestIncomplete();

        // $oldLocation = Location::factory()->oldRedingsMillBridge()->create([
        //     'user_id' => $this->loginUser()->id,
        // ]);

        // $this->assertCount(1, Location::all());

        // $this->from(route('locations.update', ['location' => $oldLocation]))
        //     ->patch(route('locations.update', ['location' => $oldLocation]), [
        //         'name' => null,
        //         'address' => $address = 'County Road 535, Ellington, Missouri 63638, US',
        //         'best_time_of_day_to_visit' => $bestTimeOfDayToVisit = 'Morning',
        //         'best_time_of_year_to_visit' => $bestTimeOfYearToVisit = 'Summer',
        //         'rating' => $rating = 3,
        //         'visited' => 1,
        //     ])
        //     ->assertSessionHasErrors('name')
        //     ->assertRedirect(route('locations.update', ['location' => $oldLocation]));
    }

    /** @test */
    public function it_does_not_require_address_to_create_a_location()
    {
        $this->markTestIncomplete();

        // $oldLocation = Location::factory()->oldRedingsMillBridge()->create([
        //     'user_id' => $this->loginUser()->id,
        // ]);

        // $this->assertCount(1, Location::all());

        // $this->from(route('locations.update', ['location' => $oldLocation]))
        //     ->patch(route('locations.update', ['location' => $oldLocation]), [
        //         'name' => $name = 'Blue Spring',
        //         // 'address' => $address = 'County Road 535, Ellington, Missouri 63638, US',
        //         'best_time_of_day_to_visit' => $bestTimeOfDayToVisit = 'Morning',
        //         'best_time_of_year_to_visit' => $bestTimeOfYearToVisit = 'Summer',
        //         'rating' => $rating = 3,
        //         'visited' => 1,
        //     ])
        //     ->assertSessionDoesntHaveErrors('address')
        //     ->assertRedirect(route('locations.update', ['location' => $oldLocation]))
        //     ->assertSessionHas('success_message');
    }

    /** @test */
    public function it_requires_address_to_not_be_null_if_it_is_present_to_create_a_location()
    {
        $this->markTestIncomplete();

        // $oldLocation = Location::factory()->oldRedingsMillBridge()->create([
        //     'user_id' => $this->loginUser()->id,
        // ]);

        // $this->assertCount(1, Location::all());

        // $this->from(route('locations.update', ['location' => $oldLocation]))
        //     ->patch(route('locations.update', ['location' => $oldLocation]), [
        //         'name' => $name = 'Blue Spring',
        //         'address' => null,
        //         'best_time_of_day_to_visit' => $bestTimeOfDayToVisit = 'Morning',
        //         'best_time_of_year_to_visit' => $bestTimeOfYearToVisit = 'Summer',
        //         'rating' => $rating = 3,
        //         'visited' => 1,
        //     ])
        //     ->assertSessionHasErrors('address')
        //     ->assertRedirect(route('locations.update', ['location' => $oldLocation]));
    }

    /** @test */
    public function it_requires_tags_to_be_an_number_or_string_to_create_a_location()
    {
        $this->markTestIncomplete();
        // $oldLocation = Location::factory()->create([
        //     'name' => 'Old Name',
        //     'user_id' => $this->loginUser()->id,
        // ]);

        // $this->patch(route('locations.update', ['location' => $oldLocation]), [
        //     'name' => 'New Name',
        //     'address' => 'County Road 535, Ellington, Missouri 63638, US',
        //     'best_time_of_day_to_visit' => 'Morning',
        //     'best_time_of_year_to_visit' => 'Summer',
        //     'rating' => 3,
        //     'visited' => 1,
        //     'tags' => ['Tag 1', 'Tag 2', ['Tag 3']],
        // ])
        //     ->assertSessionHasErrors('tags.*');

        // $this->assertEquals('Old Name', $oldLocation->fresh()->name);
    }

    /** @test */
    public function it_requires_link_urls_to_be_valid_urls_to_create_a_location()
    {
        $this->loginUser();

        $this->post(route('locations.store'), [
            'name' => 'Blue Spring',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'links' => [
                [
                    'name' => 'Name',
                    'url' => 'notaurl',
                ]
            ],
        ])
            ->assertSessionHasErrors('links.*.url');

        $this->assertEmpty(Location::all());
    }

    /** @test */
    public function it_creates_links_if_they_do_not_exist_when_creating_a_location()
    {
        $this->withoutExceptionHandling();
        $this->loginUser();

        $links = Link::factory()->count(3)->create();

        $this->post(route('locations.store'), [
            'name' => 'Blue Spring',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'links' => array_merge(
                $links->map->only(['id', 'name', 'url'])->toArray(),
                [$expected = Link::factory()->make()->only(['name', 'url'])]
            ),
        ]);

        $location = Location::first();
        $link = Link::where('url', $expected['url'])->first();
        $this->assertNotNull($link);
        $this->assertEquals($link->name, $expected['name']);
        $this->assertEquals($link->url, $expected['url']);

        // Links
        $this->assertEquals(
            $links->pluck('id')->prepend($link->id)->sort()->values()->toArray(),
            $location->links->pluck('id')->sort()->values()->toArray()
        );
    }

    /** @test */
    public function it_creates_tags_if_they_do_not_exist_when_creating_a_location()
    {
        $this->loginUser();

        $tags = Tag::factory()->count(3)->create()->pluck('id');

        $this->post(route('locations.store'), [
            'name' => 'Blue Spring',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'tags' => array_merge($tags->toArray(), [$newTagName = 'New Tag']),
        ]);

        $location = Location::first();

        $newTag = Tag::where('name', $newTagName)->first();
        $this->assertNotNull($newTag);

        // Tags
        $this->assertEquals(
            $tags->prepend($newTag->id)->sort()->values()->toArray(),
            $location->tags->pluck('id')->sort()->values()->toArray()
        );
    }

    /** @test */
    public function it_requires_access_difficulty_to_be_valid_enum_value_if_supplied_to_create_a_location()
    {
        $this->loginUser();

        $this->assertEmpty(Location::all());

        // Check an invalid value
        $this->post(route('locations.store'), [
            'name' => 'Blue Spring',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'access_difficulty' => 'InvalidEnumValue',
        ])
            ->assertSessionHasErrors('access_difficulty');

        $this->assertEmpty(Location::all());

        // Check empty value.
        $this->post(route('locations.store'), [
            'name' => 'Blue Spring',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'access_difficulty' => null,
        ])
            ->assertSessionDoesntHaveErrors('access_difficulty')
            ->assertSessionHas('success_message');

        $this->assertCount(1, Location::all());
    }

    /** @test */
    public function it_requires_traffic_level_to_be_valid_enum_value_if_supplied_to_create_a_location()
    {
        $this->loginUser();

        $this->assertEmpty(Location::all());

        // Check an invalid value
        $this->post(route('locations.store'), [
            'name' => 'Blue Spring',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'traffic_level' => 'InvalidEnumValue',
        ])
            ->assertSessionHasErrors('traffic_level');

        $this->assertEmpty(Location::all());

        // Check empty value.
        $this->post(route('locations.store'), [
            'name' => 'Blue Spring',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'traffic_level' => null,
        ])
            ->assertSessionDoesntHaveErrors('traffic_level')
            ->assertSessionHas('success_message');

        $this->assertCount(1, Location::all());
    }

    /** @test */
    public function it_requires_walk_distance_to_be_numeric_if_supplied_to_create_a_location()
    {
        $this->loginUser();

        $this->assertEmpty(Location::all());

        // Check an invalid value
        $this->post(route('locations.store'), [
            'name' => 'Blue Spring',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'walk_distance' => '4.25 miles',
        ])
            ->assertSessionHasErrors('walk_distance');

        $this->assertEmpty(Location::all());

        // Check empty value.
        $this->post(route('locations.store'), [
            'name' => 'Blue Spring',
            'address' => 'County Road 535, Ellington, Missouri 63638, US',
            'walk_distance' => null,
        ])
            ->assertSessionDoesntHaveErrors('walk_distance')
            ->assertSessionHas('success_message');

        $this->assertCount(1, Location::all());
    }
}
